const delete_btns = document.querySelectorAll(".delete_btn");

delete_btns.forEach((btn) => {
  btn.addEventListener("click", () => {
    const shop_id = btn.dataset.id;
    const shop_item_div = btn.closest(".bg-white");
    delete_shop(shop_id, shop_item_div);
  });
});

const delete_shop = (id, shopElement) => {
  const DELETE_URL = `./../backend/delete_api.php?id=${id}`;

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mx-2",
      cancelButton: "bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mx-2",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons
    .fire({
      title: "本当に削除しますか？",
      text: "削除したデータは元に戻せません。",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "削除する",
      cancelButtonText: "キャンセル",
      reverseButtons: true,
    })
    .then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch(DELETE_URL, {
            method: "GET",
          });

          if (response.ok) {
            Swal.fire({
              title: "削除しました！",
              text: "ショップは正常に削除されました。",
              icon: "success",
              timer: 2000,
              showConfirmButton: false,
            });
            if (shopElement) {
              shopElement.remove();
            }
          } else {
            let errorMessage = "削除に失敗しました。";
            try {
              const errorJson = await response.json();
              errorMessage += " (" + errorJson.message + ")";
            } catch (e) {
              errorMessage += " (Status: " + response.status + ")";
            }

            Swal.fire({
              title: "エラーが発生しました",
              text: errorMessage,
              icon: "error",
            });
          }
        } catch (error) {
          Swal.fire({
            title: "通信エラー",
            text: "サーバーとの通信に失敗しました。",
            icon: "error",
          });
        }
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        swalWithBootstrapButtons.fire({
          title: "キャンセルしました",
          text: "データは安全です。",
          icon: "error",
        });
      }
    });
};
