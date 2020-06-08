<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Report_status extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
	
	//Page header
    public function Header() {
		$title = $this->CustomHeaderTitle;
		//$note = $this->dataNote;
        // Logo
        $image_file = K_PATH_IMAGES.'lapan.png';
        //$this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        //$this->SetFont('helvetica', 'B', 20);
		//$this->SetFont('times', '', 12);
        // Title
        //$this->Cell(0, 15, $title, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$halaman = $this->getAliasNumPage().'/'.$this->getAliasNbPages();
		$subtable = '<table border="0" align="center" style="padding-top:4px">
						<tr>
							<td width="20%">
							</td>
							<td width="10%" align="right">
								<img src="'.$image_file.'" width="80px" />
							</td>
							<td width="50%" style="font family: arial;">
								<span style="font-size: 9pt; font-weight: bold;">LEMBAGA PENERBANGAN DAN ANTARIKSA NASIONAL
								(LAPAN)</span><br>
								<span style="font-size: 7pt;">Jl. Raya LAPAN, Sukamulya, Rumpin - Bogor 16350<br>
								Telp. (021) 75790383, 75790031 Fax. (021) 75790383<br>
								Laman: www.lapan.go.id
								</span>
							</td>
							<td width="20%">
							</td>
						</tr>
					</table>';
		// output the HTML content
		//$this->writeHTML($subtable, true, false, true, false, '');
        $this->writeHTML($subtable, true, false, true, false, '');
		//$this->writeHTMLCell(0, 0, '', '', $subtable, 'LRTB', 1, 0, true, 'L', true);
    }

    // Page footer
    public function Footers() {
		//$note = $this->dataNote;
        // Position at 15 mm from bottom
        $this->SetY(-50);
        // Set font
        //$this->SetFont('helvetica', 'I', 8);
        $this->SetFont('times', '', 12);
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$subtable = '<table border="1" style="padding:0px 2px">
						<tr>
							<td colspan="3">Disetujui oleh:</td>
							<td colspan="3">Ditujukan kepada:</td>
						</tr>
						<tr>
							<td>Nama:</td>
							<td colspan="2"> '.'</td>
							<td>Nama:</td>
							<td colspan="2"> '.'</td>
						</tr>
						<tr>
							<td>NIP:</td>
							<td colspan="2"> </td>
							<td>NIP:</td>
							<td colspan="2"> </td>
						</tr>
						<tr>
							<td>Peran:</td>
							<td colspan="2"> '.'</td>
							<td>Peran:</td>
							<td colspan="2"> '.'</td>
						</tr>
						<tr>
							<td height="50px" colspan="3">Tanda Tangan:</td>
							<td colspan="3">Tanda Tangan:</td>
						</tr>
					</table>';
		// output the HTML content
		//$this->writeHTML($subtable, true, false, true, false, '');
        $this->writeHTML($subtable, true, false, true, false, '');
    }
}
