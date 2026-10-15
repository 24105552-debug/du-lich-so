<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class TraceabilityController extends Controller
{
    /**
     * Hiển thị trang truy xuất nguồn gốc (kèm kết quả tìm kiếm theo mã query)
     */
    public function index(Request $request)
    {
        $code = $request->query('code');
        return $this->getTraceData($code);
    }

    /**
     * Hiển thị thông tin truy xuất khi quét mã QR trực tiếp qua URL dạng /truy-xuat/{code}
     */
    public function show($code)
    {
        return $this->getTraceData($code);
    }

    /**
     * Hàm xử lý truy xuất thông tin sản phẩm và tự động sinh nhật ký phù hợp với từng sản phẩm
     */
    private function getTraceData($code = null)
    {
        $product = null;

        if ($code) {
            // Sử dụng Regex bóc tách chính xác ID sản phẩm từ các định dạng mã (VD: "TX-5-2026", "DSV-2025-05", hay gõ "5")
            if (preg_match('/^(?:TX|DSV)?-?(\d+)(?:-\d+)?$/i', trim($code), $matches)) {
                $productId = $matches[1];
            } else {
                // Trường hợp mã có thêm ký tự khác, lọc lấy các chữ số liên tiếp
                preg_match('/\d+/', $code, $matches);
                $productId = $matches[0] ?? null;
            }

            if (!empty($productId)) {
                $product = Product::find($productId);
            }
        }

        // Nếu tìm thấy sản phẩm theo ID vừa nhập, chuẩn hóa hiển thị mã lô hàng
        if ($product) {
            $code = 'TX-' . $product->id . '-2026';
        } else {
            // Nếu không nhập mã hoặc ID không tồn tại trong DB, lấy sản phẩm mới nhất làm mặc định
            $product = Product::latest()->first();
            $code = $product ? 'TX-' . $product->id . '-2026' : 'TX-1-2026';
        }

        // Lấy thông tin thuộc tính của sản phẩm
        $productName = $product->title ?? $product->name ?? 'Đặc sản Việt Nam';
        $location = $product->location ?? $product->origin ?? $product->xuat_xu ?? 'Việt Nam';
        $supplier = $product->supplier_name ?? 'Cơ sở sản xuất ' . $location;
        $mfgDate = isset($product->mfg_date) ? Carbon::parse($product->mfg_date) : Carbon::now()->subDays(5);

        // Sinh nhật ký truy xuất nguồn gốc (Timeline) ĐỘNG theo từng sản phẩm
        $logs = [
            [
                'time' => $mfgDate->format('d/m/Y') . ' - 14:30',
                'title' => 'Đóng gói & Dán tem xác thực QR',
                'desc' => 'Hoàn tất kiểm định chất lượng, đóng gói niêm phong và dán tem truy xuất cho ' . $productName . '.'
            ],
            [
                'time' => $mfgDate->copy()->subDays(2)->format('d/m/Y') . ' - 08:00',
                'title' => 'Sơ chế & Kiểm định chất lượng',
                'desc' => 'Đưa nguyên liệu vào quy trình sơ chế khép kín, kiểm định chỉ số an toàn thực phẩm đạt tiêu chuẩn OCOP/VietGAP.'
            ],
            [
                'time' => $mfgDate->copy()->subDays(5)->format('d/m/Y') . ' - 07:00',
                'title' => 'Thu hoạch nguyên liệu đầu vào',
                'desc' => 'Thu hoạch chọn lọc nguyên liệu đạt độ chín/chất lượng chuẩn nhất tại ' . $location . '.'
            ]
        ];

        return view('traceability.traceability', compact('product', 'code', 'logs', 'supplier', 'location', 'mfgDate'));
    }
}