<?php
session_start();
include('../koneksi/koneksi.php');
?>

<?php
if(isset($_POST['button_filter'])){
$tgl_awal=$_POST['tgl_awal'];
$bln_awal=$_POST['bln_awal'];
$thn_awal=$_POST['thn_awal'];
$tanggal_awal=$thn_awal.$bln_awal.$tgl_awal;
$tanggal_awal_format=$tgl_awal."-".$bln_awal."-".$thn_awal;
$tgl_akhir=$_POST['tgl_akhir'];
$bln_akhir=$_POST['bln_akhir'];
$thn_akhir=$_POST['thn_akhir'];
$tanggal_akhir=$thn_akhir.$bln_akhir.$tgl_akhir;
$tanggal_akhir_format=$tgl_akhir."-".$bln_akhir."-".$thn_akhir;
$tanggal=true;
$kd_toko=$_POST['kd_toko'];
$query_get_faktur="SELECT tabel_pembelian.*,tabel_supplier.nm_supplier,tabel_supplier.atas_nama FROM tabel_pembelian,tabel_supplier 
WHERE tabel_pembelian.kd_supplier=tabel_supplier.kd_supplier  AND tabel_pembelian.tgl_pembelian BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' AND tabel_pembelian.no_faktur_pembelian LIKE '".$kd_toko."%'";}
else{
$tanggal=false;
$kd_toko=$_SESSION['kd_toko'];
$query_get_faktur="SELECT tabel_pembelian.*,tabel_supplier.nm_supplier,tabel_supplier.atas_nama FROM tabel_pembelian,tabel_supplier WHERE tabel_pembelian.kd_supplier=tabel_supplier.kd_supplier AND tabel_pembelian.no_faktur_pembelian LIKE '".$kd_toko."%'";}
$get_faktur=mysql_query($query_get_faktur);
$count_faktur=mysql_num_rows($get_faktur);
$total_seluruh_beli=0; $total_seluruh_item=0; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/laporan.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="pilih_laporan"><table width="95%" border="0" align="center">
  <tr>
    <td align="center"><form id="form_filter" name="form_filter" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
      Tanggal Awal : 
      <select name="tgl_awal" size="1" id="tgl_awal">
<?php
for($i=1;$i<=31;$i++){
if($i<10){ $i="0".$i; }
echo"<option value=".$i.">".$i."</option>";}
?>
      
      </select>
      <select name="bln_awal" size="1" id="bln_awal">
<?php
for($i=1;$i<=12;$i++){
if($i<10){ $i="0".$i; }
echo"<option value=".$i.">".$i."</option>";}
?>
      
      </select>
      <select name="thn_awal" size="1" id="thn_awal">
<?php
for($i=2013;$i<=date('Y');$i++){
if($i<10){ $i="0".$i; }
echo"<option value=".$i.">".$i."</option>";}
?>
      
      </select>
    Tanggal Akhir : 
    <select name="tgl_akhir" size="1" id="tgl_akhir">
<?php
for($i=1;$i<=31;$i++){
if($i<10){ $i="0".$i; }
echo"<option value=".$i.">".$i."</option>";}
?>    
    </select>
    <select name="bln_akhir" size="1" id="bln_akhir">
<?php
for($i=1;$i<=12;$i++){
if($i<10){ $i="0".$i; }
echo"<option value=".$i.">".$i."</option>";}
?>    
    </select>
    <select name="thn_akhir" size="1" id="thn_akhir">
<?php
for($i=2013;$i<=date('Y');$i++){
if($i<10){ $i="0".$i; }
echo"<option value=".$i.">".$i."</option>";}
?>    
    </select>
    Toko : 
    <select name="kd_toko" size="1" id="kd_toko">
<?php
if($_GET['view']=="cabang"&&$_SESSION['status_toko']=="pusat"){
$query_get_kd_toko=mysql_query("SELECT kd_toko FROM tabel_toko WHERE status='cabang'");
while($get_kd_toko=mysql_fetch_array($query_get_kd_toko)){
$kd_toko=$get_kd_toko['kd_toko'];
echo"<option value=".$kd_toko.">".$kd_toko."</option>";}}
else{
echo"<option value=".$_SESSION['kd_toko'].">".$_SESSION['kd_toko']."</option>";}
?>
    
    </select>
    <input type="submit" name="button_filter" id="button_filter" value="Filter" />
    <input type="submit" name="button_print" id="button_print" value="Print" onclick="print()" />
    </form></td>
  </tr>
</table>
</div>
<div id="tampil_laporan"><table width="95%" border="0" align="center">
  <tr>
    <td colspan="5" align="center" class="judul_laporan"><p>Laporan Pembelian</p>
      <p>Toko :<?php echo $kd_toko; ?></p>
      <p>Tanggal : <?php if($tanggal==true){echo $tanggal_awal_format." s/d ".$tanggal_akhir_format; } ?></p></td>
    </tr>
<?php
for($i=0; $i<$count_faktur; $i++){
$faktur=mysql_fetch_array($get_faktur);
$no_faktur=$faktur['no_faktur_pembelian'];
$id_user=$faktur['id_user'];
$tgl_pembelian=$faktur['tgl_pembelian'];
$kd_supplier=$faktur['kd_supplier'];
$nm_supplier=$faktur['nm_supplier'];
$atas_nama=$faktur['atas_nama'];
$tgl=substr($tgl_pembelian,8,2);
$bln=substr($tgl_pembelian,5,2);
$thn=substr($tgl_pembelian,0,4);
$tgl_beli_format=$tgl."-".$bln."-".$thn;
$total_pembelian=$faktur['total_pembelian']; ?>
<tr>
    <td>No. Faktur : <?php echo $no_faktur; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Kode Supplier : <?php echo $kd_supplier; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Id User : <?php echo $id_user; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Supplier : <?php echo $nm_supplier; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Tanggal : <?php echo $tgl_beli_format; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Atas Nama : <?php echo $atas_nama; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="header_footer">
    <td>Kode Barang</td>
    <td>Nama Barang</td>
    <td>Jumlah</td>
    <td>Harga</td>
    <td>Sub Total</td>
  </tr>
  <?php
$query_get_rinci_pembelian="SELECT * FROM tabel_rinci_pembelian WHERE no_faktur_pembelian='".$no_faktur."'";
$get_rinci_pembelian=mysql_query($query_get_rinci_pembelian);
$total_item=0;
while($rinci_pembelian=mysql_fetch_array($get_rinci_pembelian)){
$no_faktur=$rinci_pembelian['no_faktur_pembelian'];
$kd_barang=$rinci_pembelian['kd_barang'];
$nm_barang=$rinci_pembelian['nm_barang'];
$jml=$rinci_pembelian['jumlah'];
$hrg=$rinci_pembelian['harga'];
$sub_total_beli=$rinci_pembelian['sub_total_beli'];
$total_item=$jml+$total_item;
$total_seluruh_beli=$sub_total_beli+$total_seluruh_beli;
$total_seluruh_item=$jml+$total_seluruh_item; ?>
<tr class="isi_laporan">
    <td><?php echo $kd_barang; ?>&nbsp;</td>
    <td><?php echo $nm_barang; ?>&nbsp;</td>
    <td><?php echo $jml; ?>&nbsp;</td>
    <td><?php echo $hrg; ?>&nbsp;</td>
    <td><?php echo $sub_total_beli; ?>&nbsp;</td>
  </tr>

<?php }?>
  <tr class="header_footer">
    <td>Total</td>
    <td>&nbsp;</td>
    <td><?php echo $total_item; ?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Rp. <?php echo $total_pembelian; ?></td>
  </tr>

<?php }?>
  <tr class="resume_laporan">
    <td>Total seluruh</td>
    <td>&nbsp;</td>
    <td><?php echo $total_seluruh_item; ?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Rp. <?php echo $total_seluruh_beli; ?></td>
  </tr>
</table>
</div>
</body>
</html>