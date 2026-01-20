/**
 * 2025/11/04
 * DINH BINH QUAN
 *
 * マップ表示
 */

export const initMap = (shopData = []) => {
  const defaultShop = { lat: 34.6937, lon: 135.5023 };
  let targetShop = null;
  let targetZoom = 10;
  if (Array.isArray(shopData) && shopData.length > 0) {
    targetShop = shopData[0];
    targetZoom = 13;
  } else if (shopData && shopData.lon && shopData.lat) {
    targetShop = shopData;
    targetZoom = 15;
  } else targetShop = defaultShop;

  const { lon, lat } = targetShop;

  if (window.shopMarker && targetZoom !== 15) {
    window.shopMarker.remove();
    window.shopMarker = null;
  }

  // すでにマップが存在しない場合（初回表示）
  if (!window.shopMap) {
    // マップを初期化、 初回ロードではマーカーなし
    window.shopMap = new maplibregl.Map({
      container: "map_container", // 地図を表示するHTML要素のID
      style: "https://tiles.stadiamaps.com/styles/osm_bright/style.json", //マップスタイル
      center: [lon, lat], // 初期表示位置（経度・緯度）
      zoom: targetZoom, // 初期ズームレベル
    });
    window.shopMap.addControl(new maplibregl.NavigationControl());

    window.shopMarker = null;
  }
  // すでにマップが存在する場合（再検索など）
  // マップを新しい位置にスムーズに移動（アニメーション付き）
  else {
    window.shopMap.flyTo({
      center: [lon, lat],
      zoom: targetZoom,
      speed: 1.2, // 移動スピード（大きいほど速い）
      curve: 1.0, // アニメーションの滑らかさ
      essential: true, // ユーザー設定に関係なく動作
    });

    if (targetZoom === 15) {
      if (!window.shopMarker) {
        window.shopMarker = new maplibregl.Marker({ color: "red" }).setLngLat([lon, lat]).addTo(window.shopMap);
      } else {
        window.shopMarker.setLngLat([lon, lat]);
      }
      // ポップアップを表示（クリック不要で表示）
      window.shopMarker.togglePopup();
    }
  }
};

export const renderDetailMap = (shop) => {
  // 1️⃣ Kiểm tra dữ liệu
  if (!shop || !shop.lat || !shop.lon) {
    console.warn("Shop data không hợp lệ");
    return;
  }

  // 2️⃣ Chuyển lat/lon sang số
  const lat = parseFloat(shop.lat);
  const lon = parseFloat(shop.lon);

  // 3️⃣ Tạo map
  const map = new maplibregl.Map({
    container: document.getElementById("map_container"), // div chứa map
    style: "https://tiles.stadiamaps.com/styles/osm_bright/style.json", //マップスタイル
    center: [lon, lat],
    zoom: 15,
  });

  // 4️⃣ Thêm marker
  new maplibregl.Marker()
    .setLngLat([lon, lat])
    .setPopup(
      new maplibregl.Popup({ offset: 25 }) // popup khi click marker
        .setHTML(`<h3>${shop.name}</h3><p>${shop.address}</p>`)
    )
    .addTo(map);
};

