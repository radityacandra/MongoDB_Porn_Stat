<?php
class porn_filtering_model{
	/**
	 * untuk konek ke server database dan sekalian pilih nama database yang bersangkutan
	 * @return database connection
	 */
	public function koneksi(){
		$koneksi = new MongoClient();
		$db = $koneksi->porn_filtering;
		return $db;
	}
	
	/**
	 * buat get semua user dari database atau user spesifik dari database, tergantung ada parameter atau tidak
     * @param string $id_user
	 * @return array
	 * struktur array (
	 * 	id_user => $data,
	 * 	nama_user => $data,
	 * 	kota => $data,
	 * 	kecepatan_akses => $data,
	 * )
	 */
	public function get_user($id_user=null){
		$db = $this->koneksi();
		$collection = $db->data_user;
		$counter = 0;
		$return_array = array();
        if ($id_user==null){
            $cursor = $collection->find();
        }
        else{
            $criteria = array('id_user'=>$id_user,);
            $cursor = $collection->find($criteria);
        }
		foreach ($cursor as $document){
			$return_array[$counter] = $document;
			$counter++;
		}
		return $return_array;
	}

    /**
	 * buat get data website
	 * @return multitype:array
	 */
	public function get_website_id($id_website=null){
		$db = $this->koneksi();
		$collection = $db->porn_description;
		$counter = 0;
		$return_array = array();
        if ($id_website==null){
            $cursor = $collection->find();
        }
        else{
            $criteria = array('description_id'=>$id_website,);
            $cursor = $collection->find($criteria);
        }
		foreach ($cursor as $document){
			$return_array[$counter] = $document;
			$counter++;
		}
		return $return_array;
	}

    /**
     * untuk mengambil data dari fact table, butuh parameter, foreign key dari masing-masing dimensi
     * @param $id_user
     * @param $id_website
     * @return array
     */
    public function get_user_website_statistic($id_user, $id_website){
        $db = $this->koneksi();
        $collection = $db->web_stat;
        $counter = 0;
        $return_array = array();
        $criteria = array(
            "ID_user"=>$id_user,
            "ID_domain"=>$id_website,
        );
        $cursor = $collection->find($criteria);
        foreach ($cursor as $document){
            $return_array[$counter] = $document;
            $counter++;
        }
        return $return_array;
    }

	/**
	 * untuk mengetahui jumlah url yang diakses, baik itu url bersih, atau yang ilegal
	 * @param string $id_user
	 * @return int $jumlah_url
	 */
	public function total_url($id_user){
		$db = $this->koneksi();
		$collection = $db->url_logging;
		$criteria = array('ID_user' => $id_user);
		$jumlah_url = $collection->count($criteria);
		return $jumlah_url;
	}
	
	/**
	 * compare url_logging dengan collection blacklist, buat tahu url yang terfilter ada berapa
	 * @param string $id_user
	 * @return array $filtered_url
	 * struktur array(
	 * 		jumlah => $jumlah,
	 * 		domain => { 0 => www.xxx.com, dst }
	 * )
	 */
	public function total_url_filtered($id_user){
		$db = $this->koneksi();
		$collection = $db->url_logging;
		$collection2 = $db->blacklist;
		$criteria = array('ID_user' => $id_user);
		$cursor = $collection->find($criteria);
		$cursor_blacklist = $collection2->find();
		$jumlah_filtered_url = 0;
		$host_filtered = array();
		
		foreach ($cursor as $document){
			//var_dump($document);
			$url = $document['link_url'];
			//echo $url;
			$url = parse_url($url, PHP_URL_HOST);
			/* echo $url;
			echo '<br />'; */
			foreach ($cursor_blacklist as $document2){
				//echo $url;
				$url_blacklist = $document2['domain'];
				//echo $url_blacklist.'<br />';
				if ($url == $url_blacklist){
					$host_filtered[$jumlah_filtered_url] = $url;
					$jumlah_filtered_url++;
				}
			}
		}
		$filtered_url = array(
			'jumlah' => $jumlah_filtered_url,
			'domain' => $host_filtered,
		);
		return $filtered_url;
	}
	
	/**
	 * dari function total_url_filtered, dilihat setiap yang terfilter itu masuk kategori apa
	 * @param string $id_user
	 */
	public function total_category_porn($id_user){
		$db = $this->koneksi();
		$collection = $db->porn_description;
		$domain = $this->total_url_filtered($id_user);
		$domain = $domain['domain'];
		$counter_video = 0;
		$counter_photo = 0;
		$counter_date = 0;
		
		foreach ($domain as $key => $value){
			$criteria = array('link_url'=>$value);
			//echo $value;
			$cursor = $collection->find($criteria);
			foreach ($cursor as $key => $document){
				if ($document['porn_category']=='video'&&sizeof($document)!=0){
					$counter_video++;
				}
				elseif ($document['porn_category']=='photo'&&sizeof($document)!=0){
					$counter_photo++;
				}
				elseif ($document['porn_category']=='dating'&&sizeof($document)!=0){
					$counter_date++;
				}
			}
		}
		$return_array = array(
			'video' => $counter_video,
			'photo' => $counter_photo,
			'dating' => $counter_date,
		);
		return $return_array;
	}
}

/* $chandra = new porn_filtering_model();
$total_url_chandra = $chandra->total_url("BLB-15");
$filtered_url_chandra = $chandra->total_url_filtered("BLB-15");
$filtered_url_chandra = $filtered_url_chandra['jumlah'];

echo 'total url diakses: '.$total_url_chandra.'<br />';
echo 'total url terfilter: '.$filtered_url_chandra.'<br />';
$category = $chandra->total_category_porn("BLB-15");
echo 'total video porno: '.$category['video'].'<br />';
echo 'total foto porno: '.$category['photo'].'<br />';
echo 'total kencan porno: '.$category['dating'].'<br />'; */
?>