/**
 * 2025/10/29
 * DINH BINH QUAN
 *
 * 検索フォームの送信処理
 * フォーム送信時に入力フィールドをクリアする
 */
import { renderShop } from "./renderShop";
import { initMap } from "./renderMap";

const sessionStorage = window.sessionStorage;
const searchFormWrapper = document.getElementById("result_form"); // 検索フォームのラッパー

const area = document.getElementById("area_input"); // エリア入力フィールド
const keyword = document.getElementById("result_keyword"); // キーワード入力フィールド

const loadingAlert = () => {
  let timerInterval;
  Swal.fire({
    title: "検索中...",
    html: `
      <div class= "mt-2">   読み込み中です。しばらくお待ちください。 </div>
    `,
    timer: 800,
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading();
      const timer = Swal.getPopup().querySelector("b");
      timerInterval = setInterval(() => {
        timer.textContent = `${Swal.getTimerLeft()}`;
      }, 100);
    },
    willClose: () => {
      clearInterval(timerInterval);
    },
  });
};
// フォームが送信されたときの処理
searchFormWrapper.addEventListener("submit", async (e) => {
  e.preventDefault(); // ページのリロードを防止
  e.stopPropagation(); // イベントの伝播を止める

  let shopsData = []; // 検索結果を格納する変数
  loadingAlert();
  // 入力されたデータをFormDataとしてまとめる
  const data = new FormData();
  data.append("keyword", keyword.value);
  data.append("area", area.value);

  setTimeout(async () => {
    try {
      // PHPファイルにデータをPOSTで送信し、結果を取得
      const res = await fetch("../../client/backend/api/search_handle.php", {
        method: "POST",
        body: data,
      });
      if (!res.ok) throw new Error(`HTTP Error! Status: ${res.status}`);
      const result = await res.json(); // JSON形式で結果を受け取る
      shopsData = result;
    } catch (error) {
      // 通信エラーが発生した場合の処理
      shopsData = [];
      console.error("Error:", error);
    } finally {
      Swal.close();
      // 取得した結果を画面に表示
      sessionStorage.setItem("shopsData", JSON.stringify(shopsData));
      renderShop(shopsData);
      initMap(shopsData);
      console.log(JSON.parse(sessionStorage.getItem("shopsData")));
    }
  },700);
});
