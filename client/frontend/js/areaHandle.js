/**
 * 2025/10/29
 * DINH BINH QUAN
 */

import { createElementHelper } from "./createElement.js";

const areaWrapper = document.getElementById("area_wrapper"); // エリア入力欄を囲むラッパー
const addressInput = document.getElementById("area_input"); // 入力フィールド

/**
 *ーバーからエリア情報を取得する関数
 * データベースに保存されているエリア一覧を返す
 * @returns エリア配列（取得失敗場合、空配列）
 */
const fetchArea = async () => {
  try {
    const res = await fetch("../../client/backend/api/area_api.php");
    return await res.json();
  } catch (error) {
    return [];
  }
};

/**
 * キーワードによってリストのアイテムを表示
 * キーワードない場合、全アイテムを表示
 * @param {*} ul リスト対象
 * @param {*} keyword キーワード
 */
const filterAddressList = (ul, keyword) => {
  const listItems = ul.querySelectorAll("li");

  listItems.forEach((item) => {
    const areaName = item.textContent.toLowerCase();

    if (areaName.includes(keyword) || keyword == "") {
      item.style.display = "block";
    } else item.style.display = "none";
  });
};
/**
 * リストのアイテムをクリックした時の処理
 * @param {*} e クリックイベント
 */
const areaHandle = (e, ulElement) => {
  const clicked = e.target;
  addressInput.value = clicked.textContent; // 入力フィールドに選択したテキストをセット
  ulElement.classList.add("hidden"); // 選択後、リストを非表示
};

/**
 * エリアリストの要素を作成する
 * @returns エリアリストの要素
 */
const areaListElement = async () => {
  // ul要素を作成、クラスでスタイルを指定
  const ul = createElementHelper("ul", { className: "address_list absolute top-12 bg-white max-h-40 w-full overflow-y-auto hidden z-100 " });

  const areas = await fetchArea();

  // 各エリアをli要素として追加
  areas.forEach((area) => {
    const li = createElementHelper("li", {
      className: "cursor-pointer p-2 hover:bg-gray-200",
      text: area.name,
      attrs: { value: area.slug },
      events: { click: (e) => areaHandle(e, ul) },
    });
    ul.appendChild(li); // ulに追加
  });
  return ul; // 完成したulを返す
};

const init = async () => {
  // リストを作成してラッパーに追加
  const ul = await areaListElement();
  areaWrapper.appendChild(ul);

  // 入力時にリストをフィルター
  addressInput.addEventListener("input", (e) => {
    const area_keyword = e.target.value.toLowerCase();
    filterAddressList(ul, area_keyword);
  });

  // 入力フィールドにフォーカスした時にリストを表示
  addressInput.addEventListener("focus", () => {
    ul.classList.remove("hidden");
  });

  // ラッパー外をクリックしたらリストを非表示
  document.addEventListener("click", (e) => {
    if (!areaWrapper.contains(e.target)) {
      ul.classList.add("hidden");
    }
  });
};
init();
