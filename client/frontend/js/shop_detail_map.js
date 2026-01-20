document.querySelector(".map-btn").addEventListener("click", (e) => {
  const shop = JSON.parse(e.currentTarget.dataset.shop);
  console.log(shop);

  Swal.fire({
    html: `<div id="swal_map_container" class="w-full h-150 rounded-lg overflow-hidden"></div>`,
    width: "100%",
    showConfirmButton: false,
    showCloseButton: false,
    didOpen: () => {
      const mapContainer = document.getElementById("swal_map_container");
      if (!mapContainer) return;

      // Tạo map trong div của Swal
      const lat = parseFloat(shop.lat);
      const lon = parseFloat(shop.lon);

      const map = new maplibregl.Map({
        container: mapContainer,
        style: "https://tiles.stadiamaps.com/styles/osm_bright/style.json",
        center: [lon, lat],
        zoom: 15,
      });

      new maplibregl.Marker()
        .setLngLat([lon, lat])
        .setPopup(new maplibregl.Popup({ offset: 25 }).setHTML(`<h3>${shop.name}</h3><p>${shop.address}</p>`))
        .addTo(map);
    },
    showClass: {
      popup: `animate__animated animate__fadeInUp animate__faster`,
    },
    hideClass: {
      popup: `animate__animated animate__fadeOutDown animate__faster`,
    },
  });
});
