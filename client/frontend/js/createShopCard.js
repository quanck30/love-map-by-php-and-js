/**
 * 2025/10/29
 * DINH BINH QUAN
 *
 *  ショップカードを作成
 *
 */

import { createElementHelper } from "./createElement.js";
import { initMap } from "./renderMap.js";

/**
 *  評価に応じて星のアイコンを生成する
 * @param {number} rating rating 0〜5 の評価値
 * @returns {HTMLElement[]} 星の<i>要素の配列
 */
const renderStars = (rating) => {
  const stars = [];
  for (let i = 1; i <= 5; i++) {
    let starClass = "";
    if (i <= Math.floor(rating)) starClass = "ri-star-fill";
    else if (i - rating < 1) starClass = "ri-star-half-line";
    else starClass = "ri-star-line";
    const star = createElementHelper("i", { className: starClass });
    stars.push(star);
  }
  return stars;
};

const gotoDetailPage = (shop) => {
  window.location.href = `shop_detail.php?shop_id=${shop.id}`;
};

/**
 * 店舗カードを生成する
 *
 * @param {Object} shop 店舗情報オブジェクト
 * @param {string} shop.name 店舗名
 * @param {string} shop.address 住所
 * @param {string} shop.description 説明文
 * @param {string} shop.image 画像URL
 * @param {number} shop.rating 評価値
 * @param {number} shop.reviews レビュー件数
 * @param {string|number} shop.price 料金
 * @returns {HTMLElement} 店舗カードのHTML要素
 */
export const createShopCard = (shop) => {
  // カード全体
  const shopCard = createElementHelper("div", {
    className: "shop_content flex flex-col md:flex-row border border-pink-200 rounded-lg gap-5 mb-10 p-5 cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl",
    events: {
      click: () => gotoDetailPage(shop),
    },
  });

  // 画像エレメント
  const imgWrapper = createElementHelper("div", {
    className: "w-full md:w-72 h-56 md:h-full aspect-[4/3] overflow-hidden rounded-lg flex-shrink-0",
  });
  const img = createElementHelper("img", {
    className: "w-full h-full object-cover object-center",
    attrs: { src: shop.image, alt: shop.name },
  });
  imgWrapper.appendChild(img);

  // 店舗情報コンテンツ
  const shopContent = createElementHelper("div", {
    className: "flex-1 pt-1 md:pt-3 md:pr-8",
  });
  const shopName = createElementHelper("h2", {
    className: "shop_name text-3xl md:text-4xl font-bold",
    text: shop.name,
  });
  const shopAddress = createElementHelper("p", {
    className: "shop_address py-2 text-gray-400 ",
    text: shop.address,
  });
  const shopDescription = createElementHelper("p", {
    className: "line-clamp-2 min-h-[2.5rem]",
    text: shop.description,
  });
  // 評価部分
  const shopRatingWrapper = createElementHelper("h2", {
    className: "inline-flex pt-3 text-xl text-pink-400 items-center",
  });
  renderStars(shop.rating).forEach((star) => shopRatingWrapper.appendChild(star));

  const rating = createElementHelper("span", {
    className: "mx-4",
    text: (+shop.rating).toFixed(1),
  });
  const reviews = createElementHelper("span", {
    text: `${shop.reviews} 件`,
  });
  shopRatingWrapper.append(rating, reviews);

  // 価格表示
  const priceWrapper = createElementHelper("h3", {
    className: "py-3",
  });
  const price = createElementHelper("span", {
    className: "text-2xl",
    text: shop.price * 2,
  });
  priceWrapper.append(price, "  円/組");
  shopContent.append(shopName, shopAddress, shopDescription, shopRatingWrapper, priceWrapper);

  // アクションボタン
  const shopActions = createElementHelper("div", {
    className: "w-full md:w-20 flex md:flex-col justify-end md:justify-start items-center text-4xl text-pink-600 gap-5 md:pt-10",
  });
  const infoBtn = createElementHelper("i", {
    className: "ri-information-fill cursor-pointer my-2 hover:text-pink-300 transition-colors duration-200",
    events: {
      click: (e) => {
        e.stopPropagation();
        gotoDetailPage(shop);
      },
    },
  });
  const mapBtn = createElementHelper("i", {
    className: "map_btn ri-compass-discover-fill cursor-pointer hover:text-pink-300 transition-colors duration-200",
    events: {
      click: (e) => {
        e.stopPropagation();
        initMap(shop);
      },
    },
  });
  shopActions.append(infoBtn, mapBtn);

  // カードに全ての要素を追加
  shopCard.append(imgWrapper, shopContent, shopActions);
  return shopCard; // 完成した店舗カードを返す
};
