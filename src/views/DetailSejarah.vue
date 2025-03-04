<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-black text-white p-6">
    <!-- Tombol kembali di pojok kiri atas -->
    <button 
      @click="$router.back()" 
      class="absolute top-4 left-4 flex items-center text-white text-lg hover:text-gray-400"
    >
      <span class="text-2xl">‹</span> <!-- Simbol panah kiri -->
      <span class="ml-2">Kembali</span>
    </button>
    
      <h1 class="text-4xl font-bold">{{ article?.title }}</h1>
      <img :src="article?.image" class="mt-5 w-full max-w-lg" :alt="article?.title" />
      <p class="mt-4 text-lg">{{ article?.content }}</p>
    </div>
  </template>
  
  <script>
  import pusakaImage from "@/assets/img/Sejarah/pusaka.png";
  import gerbongImage from "@/assets/img/Sejarah/gerbong.png";
  import latbelImage from "@/assets/img/Sejarah/latbel.png";
  import bregadaImage from "@/assets/img/Sejarah/bregada.png";
  import meriamImage from "@/assets/img/Sejarah/meriam.png";
  import latbel2Image from "@/assets/img/Sejarah/latbel2.png";
  import gangsaImage from "@/assets/img/Sejarah/gangsa.png";
  import sriImage from "@/assets/img/Sejarah/sri.png";
  import jejakImage from "@/assets/img/Sejarah/jejak.png";
  
  export default {
    name: "Detail",
    data() {
      return {
        articles: [
          { title: "Pusaka - Canthik Kyai Rajamala", content: "Dalam Babad Sangkala, diceritakan bahwa Sinuhun Pakubuwana IV memerintahkan putranya, KGPAA Hamengkunagara (yang kelak naik tahta sebagai Pakubuwana V), untuk membuat sebuah kapal yang besar dan panjang,Perintah ini didasari oleh rasa ketidaksukaan Pakubuwana IV terhadap hadiah kapal dari Gubernur Jenderal Herman Willem Daendels. Kapal tersebut diberi nama Kapal Radjaputri dan ditempatkan di Sungai Kedung Penganten. Beliau kemudian menitahkan putranya, KGPAA Hamengkunagara III, untuk membuat kapal yang lebih besar dan lebih panjang. Kapal itu selesai dibuat pada Jumat, 27 Jumadilakir 1738 19 Juli 1811 Masehi. Canthik atau hiasan depan kapal diukir dengan bentuk kepala wayang Raden Arya Rajamala, sehingga kapal tersebut diberi nama Kapal Kyai Rajamala. Kapal Kyai Rajamala dan Kapal Radjaputri kemudian dinikahkan secara lengkap, layaknya pengantin Jawa, di Kedung Penganten, Bengawan Solo, oleh Sinuhun Pakubuwana IV. Pembuatan Kapal Kyai Rajamala menjadi bukti kebesaran Pakubuwana IV. Kayu yang digunakan berasal dari hutan jati Danalaya, hutan milik Keraton Kasunanan Surakarta yang terletak di ujung selatan Wonogiri. Hutan Danalaya dikenal wingit (keramat), karena tidak sembarang orang berani menebang pohon di sana. Apabila keraton membutuhkan kayu untuk mengganti bangunannya, pohon jati di hutan Donoloyo seringkali tumbang dengan sendirinya. Sebagai kapal dinas resmi Keraton Kasunanan Surakarta Hadiningrat, Kapal Kyai Rajamala digunakan untuk mengantar putri Bupati Tjakraningrat IV dari Madura, yang merupakan istri Sinuhun Pakubuwana IV, yaitu Permaisuri Kanjeng Ratu Kencana Wungu. Kapal ini menempuh rute Surakarta – Sukowati – Ngawi – Cepu – Bojonegoro – Gresik, melewati Selat Madura, hingga mencapai Bangkalan. Dalam Serat Prathelan Wontenipun Candhi, reca, patilasan, padhusan, dan sebagainya di Karesidenan Surakarta, disebutkan bahwa Kapal Kyai Rajamala kerap digunakan oleh Pakubuwana IX untuk berlayar dan berekreasi ke Pesanggrahan Langenharjo melalui aliran Bengawan Solo. Kapal ini sering berlabuh bersama beberapa kapal kecil lainnya, seperti Kyai Sekonyar, Rarasati, Kyai Bintang Timur, Nyai Wilutama, dan lain-lain.", image: pusakaImage, slug: "pusaka-kyai-rajamala" },
          { title: "Gerbong pesiar Paku Buwana X", content: "Industri kereta api di Surakarta yang mulai berkembang pada akhir 1800-an, diawali dengan munculnya trem kota berbasis tenaga kuda. Perusahaan pengelola trem swasta Solosche Tramweg Maatschappij, SoTM, pada awalnya menggunakan satu gerbong kereta yang ditarik dengan kuda dan diisi dengan 20 hingga maksimal 25 orang. Route trem ini dimulai dari halte depan benteng Vastenburg lalu ke selatan hingga ke Gladag, kemudian berbelok ke barat menuju Poerwosari weg atau Jalan Slamet Riyadi. Dari stasiun Purwosari, kereta trem melaju ke barat hingga menuju ke Rasamadu suikerfabriek di Gembongan - Kartasura, pabrik gula Rasamadu di Kartasura. Pada 1905, jalur trem ini diambil alih oleh Nederlandsch Indische Spoorweg Maatschappij, NISM, sebuah perusahaan kereta api swasta yang berkedudukan di Den Haag, yang kemudian membangun sebuah kantor administrasi di Semarang, Lawang Sewu. Perusahaan Nederlandsch Indische Spoorweg Maatschappij kemudian mengganti trem tenaga kuda di Surakarta dengan mesin uap. Rangkaian kereta pesiar ini konon diberangkatkan dari Stasiun Solo Jebres untuk mengantarkan Sinuhun Paku Buwono X dalam rangka meninjau pabrik gula atau untuk keperluan pesiar kunjungan ke berbagai daerah bersama dengan keluarganya. Gerbong kereta pesiar milik Sinuhun Paku Buwono X ini pernah disimpan di Stasiun Semarang Tawang pada 1960. Kereta ini juga sempat direhabilitasi oleh Balai Yasa Semarang pada 17 Desember 1985. Rehabilitasi yang dilakukan meliputi pergantian dinding kereta yang semula dari kayu menjadi baja. Gerbong kereta ini sempat pula menjadi pajangan di Stasiun Semarang Tawang hingga akhirnya dikembalikan dan disimpan oleh Karaton Surakarta.", image: gerbongImage, slug: "gerbong-pesiar" },
          { title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat", content: "Mataram merupakan...", image: latbelImage, slug: "latar-belakang-keraton" },
          { title: "Bregada - Prajurit Karaton Surakarta Hadiningrat", content: "Prajurit keraton...", image: bregadaImage, slug: "bregada-keraton" },
          { title: "Pusaka - Meriam Nyai Setomi", content: "Meriam yang konon...", image: meriamImage, slug: "meriam-nyai-setomi" },
          { title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat", content: "Dominasi kongsi dagang...", image: latbel2Image, slug: "latar-belakang-keraton-2" },
          { title: "Gangsa Pakurmatan Sekaten", content: "Dominasi kongsi dagang...", image: gangsaImage, slug: "gangsa-sekaten" },
          { title: "Jejak Keberanian Pakubuwana IV - Pakepung", content: "Dominasi kongsi dagang...", image: jejakImage, slug: "pakubuwana-iv-pakepung" },
          { title: "Sri Susuhunan Pakubuwana XIII", content: "Dominasi kongsi dagang...", image: sriImage, slug: "pakubuwana-xiii" },
        ],
      };
    },
    computed: {
      article() {
        return this.articles.find((item) => item.slug === this.$route.params.slug);
      },
    },
  };
  </script>
  