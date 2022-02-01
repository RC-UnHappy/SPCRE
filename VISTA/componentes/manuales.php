<div id="tt" class="titulo_m">
	<h2 id="tltModulo">Manuales</h2><i class="icon-book"></i>
</div>

<p class="marginB-2">
	<b class="text_azul">Descargar</b> manuales.
</p>

<?php 
if( $_SESSION['vsn_nivel'] <= 2 ){
?>
<!-- <a href="../manuales/archivo.docx" class="btn btn_icon_square lg">
	<i class="icon-book"></i>
	<p style="font-size:12px;">Manual de Instalación de Software</p>
</a> -->
<a href="../manuales/Manual de sistema.pdf" target="_blank" class="btn btn_icon_square lg">
	<i class="icon-book"></i>
	<p>Manual de Sistema</p>
</a>
<?php } ?>
<a href="../manuales/Manual de usuario.pdf" target="_blank" class="btn btn_icon_square lg">
	<i class="icon-book"></i>
	<p>Manual de Usuario</p>
</a>
