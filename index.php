<?php require_once 'controller.php'; ?>
<!doctype html>
<html>
<head>
	<title>Statistik Pengaksesan konten porno setiap user</title>
	<script src="js/jquery.min.js"></script>
    <link rel="stylesheet" href="css/index-style.css" type="text/css" media="screen" />

    <!--bootstrap-->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" type="text/css" media="screen" />
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
        /**
         * buat ngecek saat onchange select id_user
         */
		function on_change_id_user(){
			var selected_id = document.getElementById("id_user").value;
            var jsondata = $.ajax({
                url: "data_user.php",
                async: false,
                dataType: "json",
                type: "POST",
                data: "selected_id="+selected_id
            }).responseText;
            var json = JSON.parse(jsondata);
            document.getElementById("data_user").style.display = "inline";
            //alert(json[0].id_user);
            $('input[name=id_user]').val(json[0].id_user);
            $('input[name=kota]').val(json[0].kota);
            $('input[name=kecepatan]').val(json[0].kecepatan_akses+" Mbps");

            //kalau nilai kedua select ada isinya
            var selected_site = document.getElementById("id_site").value;
            if(selected_site!=""){
                get_fact_table(selected_site, selected_id);
            }
		}
	</script>

    <script type="text/javascript">
        function selected_id_site(){
            var selected_site = document.getElementById("id_site").value;
            var jsondata = $.ajax({
                url: "data_web.php",
                async: false,
                dataType: "json",
                type: "POST",
                data: "selected_site="+selected_site
            }).responseText;
            var json = JSON.parse(jsondata);
            document.getElementById("data_website").style.display = "inline";
            //alert(json[0].link_url);
            $('input[name=link]').val(json[0].link_url);
            $('textarea[name=description]').val(json[0].description);

            var selected_id = document.getElementById("id_user").value;
            if(selected_id!=""){
                get_fact_table(selected_site, selected_id);
            }
        }
    </script>

    <script type="text/javascript">
        function get_fact_table(selected_site, selected_id){
            document.getElementById("statistik_akses").style.display = "inline";
            var jsondata = $.ajax({
                url: "data_user_web_stat.php",
                async: false,
                dataType: "json",
                type: "POST",
                data: "selected_site="+selected_site+"&selected_id="+selected_id
            }).responseText;
            var json = JSON.parse(jsondata);
            $('input[name=jumlah_akses]').val(json[0].banyak_pengaksesan);
            $('input[name=waktu_akses]').val(json[0].waktu_total_akses);
            $('input[name=category]').val(json[0].category);
        }
    </script>
</head>

<body>
	<form action="" method="get">
        <div class="form-group">
            <h4>Silahkan pilih user yang akan dimonitor</h4>
            <select name="id_user" id="id_user" class="form-control" onchange="on_change_id_user()">
                <?php
                $view = new porn_filtering_controller();
                $user = $view->list_user(); ?>
                <option value=""></option>
                <?php foreach ($user as $document){ ?>
                <option value="<?php echo $document['id_user']; ?>"><?php echo $document['nama_user']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <h4>Silahkan pilih website yang akan dimonitor</h4>
            <select name="id_site" id="id_site" class="form-control" onchange="selected_id_site()">
                <?php
                $site = $view->list_website(); ?>
                <option value=""></option>
                <?php foreach ($site as $document){ ?>
                <option value="<?php echo $document['description_id']; ?>"><?php echo $document['link_url']; ?></option>
                <?php } ?>
                ?>
            </select>
        </div>

		<div id="data_user" class="hiddens form-inline">
			<h1>Data User</h1>
            <div class="form-group">
                <label for="id_user">ID User</label>
                <input type="text" name="id_user" class="form-control" disabled />
            </div>
            <div class="form-group">
                <label for="kota">Kota user terdaftar</label>
                <input type="text" name="kota" class="form-control" disabled />
            </div>
            <div class="form-group">
                <label for="kecepatan">Kecepatan akses</label>
                <input type="text" name="kecepatan" class="form-control" disabled />
            </div>
		</div>
		
		<div id="data_website" class="hiddens form-inline">
			<h1>Data Website</h1>
            <div class="form-group">
                <label for="link">Link url</label>
                <input type="text" name="link" class="form-control" disabled />
            </div>
            <div class="form-group">
                <label for="description">Deskripsi Website</label>
                <textarea rows="4" cols="50" name="description" class="form-control" disabled></textarea>
            </div>
		</div>
		
		<div id="statistik_akses" class="hiddens form-inline">
			<h1>Statistik Pengaksesan</h1>
            <div class="form-group">
                <label for="jumlah_akses">Jumlah pengaksesan</label>
                <input type="text" name="jumlah_akses" class="form-control" disabled />
            </div>
            <div class="form-group">
                <label for="waktu_akses">Lama total pengaksesan</label>
                <input type="text" name="waktu_akses" class="form-control" disabled />
            </div>
            <div class="form-group">
                <label for="category">Kategori pornografi</label>
                <input type="text" name="category" class="form-control" disabled />
            </div>
		</div>
	</form>
</body>
</html>