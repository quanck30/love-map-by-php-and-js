/**
 * 2025/11/11
 * DINH BINH QUAN
 * ショップ表示順番処理
 */
import { createElementHelper } from "./createElement.js";
import { createShopCard } from "./createShopCard.js";

const sessionStorage = window.sessionStorage;

const shopContainer = document.getElementById("shop_container"); //店舗リストを表示するコンテナ
//  HTML要素を取得
const sortPrice = document.getElementById("sort_price"); //価格順の要素を取得
const sortRating = document.getElementById("sort_rating"); //評価順の要素を取得
const sortSlider = document.getElementById("sort_slider"); //スライドの要素を取得

const sortToggle = document.getElementById("sort_toggle"); //昇順/降順切り替えボタン
const sortArrowWrapp = document.querySelector(".sort_arrow"); //矢印アイコンのラッパー
const sortArrow = document.querySelector(".sort_arrow i"); //矢印アイコン

// ソートタイプの定義
const SORTED_PRICE = "sort_price"; // 価格順
const SORTED_RATING = "sort_rating"; // 評価順

// ソート状態の変数
let ascending = true; // true: 昇順, false: 降順
let currentSort = SORTED_PRICE; // 現在のソートタイプ

const renderShop = (listData) => {
  shopContainer.textContent = ""; // 既存の内容を削除
  if (!listData.length) {
    const p = createElementHelper("p", { text: "該当店舗がありません" });
    shopContainer.appendChild(p);
  } else {
    listData.forEach((shop) => {
      const shopCard = createShopCard(shop);
      shopContainer.appendChild(shopCard); // 各店舗カードを追加
    });
  }
};
/**
 * ショップデータが評価順の昇順並べ替えする
 * @param {*} data 対象ショップデータ
 * @returns 並べ替えしたショップデータ
 */
const sortByRating = (data) => {
  const sorted = [...data];
  return sorted.sort((a, b) => (ascending ? a.rating - b.rating : b.rating - a.rating));
};

/**
 * 価格で並べ替えする
 * @param {*} data 店舗リスト
 * @returns 並べ替えしたリスト
 */
const sortByPrice = (data) => {
  const sorted = [...data];
  return sorted.sort((a, b) => (ascending ? a.price - b.price : b.price - a.price));
};

/**
 * 昇順/降順切り替え処理
 * @param {*} e イベント
 */
const handleSortToggle = async (e) => {
  sortArrow.className = ""; // アイコンのクラスをリセット
  const target = e.currentTarget; // クリックされた要素を取得
  const text = target.childNodes[0]; // ボタン内のテキストノードを取得

  const listData = JSON.parse(sessionStorage.getItem("shopsData"));
  //現在の状態から処理
  if (ascending) {
    //昇順の場合
    text.textContent = "降順";
    sortArrow.className = "ri-arrow-down-circle-fill";
    sortArrowWrapp.classList.add("rotate-180");
  } else {
    //降順の場合
    text.textContent = "昇順";
    sortArrow.className = "ri-arrow-down-circle-fill";
    sortArrowWrapp.classList.remove("rotate-180");
  }
  ascending = !ascending; //昇順/降順を反転

  // 現在の並べ替えタイプに応じてリストを再表示
  if (currentSort == SORTED_PRICE) renderShop(sortByPrice(listData));
  else renderShop(sortByRating(listData));
};
/**
 * ボタンをクリックしたらスライドが動く
 * @param {*} clickedBtn クリックしたボタン
 */
const setActiveBtn = (clickedBtn) => {
  //価格順のボタンの長さを取得
  const sortPriceWidth = sortPrice.offsetWidth;
  let transformValue = "";

  const listData = JSON.parse(sessionStorage.getItem("shopsData"));
  // 価格順のボタンを選択するか、しないか場合
  if (clickedBtn.id === SORTED_PRICE) {
    currentSort = SORTED_PRICE;
    const resultSortByPrice = sortByPrice(listData);
    renderShop(resultSortByPrice);
    transformValue = "translateX(0)";
  } else if (clickedBtn.id === SORTED_RATING) {
    currentSort = SORTED_RATING;
    const resultSortByRating = sortByRating(listData);
    renderShop(resultSortByRating);
    transformValue = `translateX(${sortPriceWidth}px)`;
  }
  // スライドの位置が返る
  sortSlider.style.transform = transformValue;
};

// クリックするときの処理
sortPrice.addEventListener("click", () => setActiveBtn(sortPrice));
sortRating.addEventListener("click", () => setActiveBtn(sortRating));
sortToggle.addEventListener("click", (e) => handleSortToggle(e));

export const initialRender = (data) => {
  setShopsData(data);
  renderShop(sortByPrice(getShopsData()));
};
