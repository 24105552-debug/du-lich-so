import os
import sqlite3
import pandas as pd
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import linear_kernel

app = FastAPI(title="Cội Nguồn - Recommendation Microservice")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DB_PATH = os.path.join(BASE_DIR, "database", "database.sqlite")
ENV_PATH = os.path.join(BASE_DIR, ".env")

def parse_env():
    """Đọc cấu hình kết nối CSDL từ file .env của Laravel"""
    env_vars = {}
    if os.path.exists(ENV_PATH):
        with open(ENV_PATH, "r", encoding="utf-8", errors="ignore") as f:
            for line in f:
                line = line.strip()
                if line and not line.startswith("#") and "=" in line:
                    k, v = line.split("=", 1)
                    env_vars[k.strip()] = v.strip().strip('"').strip("'")
    return env_vars

def normalize_df(df):
    """Chuẩn hóa đa dạng tên cột dữ liệu từ CSDL"""
    col_title = next((c for c in ['title', 'name', 'ten_san_pham', 'ten'] if c in df.columns), None)
    df['title'] = df[col_title] if col_title else 'Sản phẩm ' + df['id'].astype(str)
    df['name'] = df['title']

    col_loc = next((c for c in ['location', 'origin', 'xuat_xu', 'address', 'dia_chi'] if c in df.columns), None)
    df['location'] = df[col_loc] if col_loc else 'Đặc sản'
    df['origin'] = df['location']

    col_price = next((c for c in ['base_price', 'price', 'sale_price', 'sell_price', 'gia_ban', 'gia'] if c in df.columns), None)
    df['base_price'] = df[col_price] if col_price else 0
    df['price'] = df['base_price']

    col_img = next((c for c in ['image', 'image_url', 'hinh_anh', 'anh'] if c in df.columns), None)
    df['image'] = df[col_img] if col_img else ''
    df['image_url'] = df['image']

    df['title'] = df['title'].fillna('').astype(str)
    df['location'] = df['location'].fillna('').astype(str)
    df['combined_features'] = df['title'] + ' ' + df['location']
    return df

def load_products():
    env = parse_env()
    db_conn = env.get("DB_CONNECTION", "mysql").lower()

    # 1. Ưu tiên đọc từ MySQL (Dulịchsố) nếu Laravel cấu hình MySQL
    if db_conn == "mysql" or "DB_DATABASE" in env:
        try:
            import pymysql
            host = env.get("DB_HOST", "127.0.0.1")
            port = int(env.get("DB_PORT", 3306))
            user = env.get("DB_USERNAME", "root")
            password = env.get("DB_PASSWORD", "")
            database = env.get("DB_DATABASE", "dulichso")

            conn = pymysql.connect(
                host=host, port=port, user=user, password=password, database=database, charset='utf8mb4'
            )
            cursor = conn.cursor()
            cursor.execute("SHOW TABLES;")
            tables = [t[0] for t in cursor.fetchall()]

            target_table = None
            for name in ['products', 'san_phams', 'san_pham', 'product']:
                if name in tables:
                    target_table = name
                    break

            if target_table:
                df = pd.read_sql_query(f"SELECT * FROM `{target_table}`", conn)
                conn.close()
                if not df.empty:
                    print(f"✅ [MySQL] Đã đọc thành công {len(df)} sản phẩm từ bảng '{target_table}'")
                    return normalize_df(df)
            conn.close()
        except Exception as e:
            print(f"⚠️ Không thể kết nối MySQL: {e}. Chuyển sang kiểm tra SQLite...")

    # 2. Dự phòng đọc từ SQLite nếu không kết nối được MySQL
    if os.path.exists(DB_PATH):
        try:
            conn = sqlite3.connect(DB_PATH)
            cursor = conn.cursor()
            cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
            tables = [t[0] for t in cursor.fetchall()]

            target_table = None
            for name in ['products', 'san_phams', 'san_pham', 'product']:
                if name in tables:
                    target_table = name
                    break

            if target_table:
                df = pd.read_sql_query(f"SELECT * FROM {target_table}", conn)
                conn.close()
                if not df.empty:
                    print(f"✅ [SQLite] Đã đọc thành công {len(df)} sản phẩm từ SQLite")
                    return normalize_df(df)
            conn.close()
        except Exception as e:
            print(f"⚠️ Lỗi SQLite: {e}")

    return pd.DataFrame()

@app.get("/api/recommend/{product_id}")
def recommend_products(product_id: int, limit: int = 4):
    df = load_products()

    if df.empty:
        return {"status": "empty", "data": []}

    try:
        product_id = int(product_id)
        df['id'] = df['id'].astype(int)
    except Exception:
        pass

    other_df = df[df['id'] != product_id]
    if other_df.empty:
        other_df = df

    selected_cols = ['id', 'title', 'name', 'location', 'origin', 'base_price', 'price', 'image', 'image_url']
    available_cols = [c for c in selected_cols if c in df.columns]

    if len(other_df) <= limit:
        result = other_df[available_cols].to_dict(orient="records")
        return {"status": "success", "target_product_id": product_id, "data": result}

    try:
        tfidf = TfidfVectorizer(token_pattern=r"(?u)\b\w+\b")
        tfidf_matrix = tfidf.fit_transform(df['combined_features'])
        cosine_sim = linear_kernel(tfidf_matrix, tfidf_matrix)

        matching_rows = df.index[df['id'] == product_id].tolist()
        if matching_rows:
            idx = matching_rows[0]
            sim_scores = sorted(list(enumerate(cosine_sim[idx])), key=lambda x: x[1], reverse=True)
            product_indices = [i[0] for i in sim_scores if i[0] != idx][:limit]

            if len(product_indices) < limit:
                needed = limit - len(product_indices)
                extra = [i for i in other_df.index if i not in product_indices][:needed]
                product_indices.extend(extra)

            result = df.iloc[product_indices][available_cols].to_dict(orient="records")
        else:
            result = other_df.head(limit)[available_cols].to_dict(orient="records")
    except Exception:
        result = other_df.head(limit)[available_cols].to_dict(orient="records")

    return {"status": "success", "target_product_id": product_id, "data": result}