<?php

namespace App\Controllers;

use App\Models\TransactionsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends BaseController
{
  public function index()
  {
    $data = [
      "title" => "Laporan Transaksi",
      "nav_active" => "report",
      "data" =>  []
    ];

    return view('report', $data);
  }

  public function export()
  {
    // dd($this->request->getPost());
    $is_valid = $this->validate([
      "start_date" => [
        "label" => "Tanggal Mulai",
        "rules" => "required|valid_date[Y-m-d]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "valid_date" => "Format {field} harus berupa [{param}]."
        ]
      ],
      "end_date" => [
        "label" => "Tanggal Akhir",
        "rules" => "required|valid_date[Y-m-d]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "valid_date" => "Format {field} harus berupa [{param}]."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $start_date = $this->request->getPost("start_date");
    $end_date = $this->request->getPost("end_date");

    $m_transactions = new TransactionsModel();
    $report = $m_transactions->report_transactions($start_date, $end_date);

    helper('number');
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue("A1", "No");
    $sheet->setCellValue("B1", "Kode TRX");
    $sheet->setCellValue("C1", "Subtotal");
    $sheet->setCellValue("D1", "Diskon");
    $sheet->setCellValue("E1", "Biaya Tambahan");
    $sheet->setCellValue("F1", "Total");
    $sheet->setCellValue("G1", "Tanggal");

    // create sheet rows
    $index = 1;
    $total_income = 0;
    foreach ($report as $value) {
      $index++;
      $total = $value->subtotal + $value->additional_cost - $value->discount;
      $total_income += $total;
      $sheet->setCellValue("A" . $index, $index - 1);
      $sheet->setCellValue("B" . $index, $value->trx_code);
      $sheet->setCellValue("C" . $index, number_to_currency($value->subtotal, 'IDR', 'id-ID'));
      $sheet->setCellValue("D" . $index, number_to_currency($value->discount, 'IDR', 'id-ID'));
      $sheet->setCellValue("E" . $index, number_to_currency($value->additional_cost, 'IDR', 'id-ID'));
      $sheet->setCellValue("F" . $index, number_to_currency($total, 'IDR', 'id-ID'));
      $sheet->setCellValue("G" . $index, $value->created_at);
    }
    $sheet->setCellValue("E" . ($index + 1), "Total Pendapatan");
    $sheet->setCellValue("F" . ($index + 1), number_to_currency($total_income, 'IDR', 'id-ID'));

    // send response xlsx file
    $this->response->setHeader("Content-Type", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    $this->response->setHeader("Content-Disposition", 'attachment; filename="Laporan Transaksi ' . $start_date . ' - ' . $end_date . '.xlsx"');

    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");
  }
}
