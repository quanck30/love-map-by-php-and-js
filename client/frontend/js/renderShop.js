/**
 * 2025/10/29
 * DINH BINH QUAN
 */

import { createElementHelper } from "./createElement.js";
import { createShopCard } from "./createShopCard.js";

const shopContainer = document.getElementById("shop_container"); //店舗リストを表示するコンテナ

/**
 * 店舗リストを表示
 * @param {*} listData 対象店舗リスト
 */
export const renderShop = (listData) => {
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
