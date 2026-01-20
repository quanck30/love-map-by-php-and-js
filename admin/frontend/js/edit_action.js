/**
 * 2025/12/05
 * DINH BINH QUAN
 */

const formEdit = document.getElementById("edit-form");
const handleEdit = async (api) => {
  const formData = new FormData(formEdit);
  console.log(api);

  for (const pair of formData.entries()) {
    console.log(pair[0], pair[1]);
  }
  try {
    const res = await fetch(api, {
      method: "POST",
      body: formData,
    });
    if (!res.ok) {
      Swal.fire({
        icon: "error",
        title: "おっと…",
        text: res.message,
      });
      return;
    }
    Swal.fire({
      icon: "success",
      title: "保存しました！",
      timer: 1200,
      showConfirmButton: false,
    }).then(() => {
      window.location.href = "/love-map/admin/frontend/all_shop.php";
    });
  } catch (error) {
    console.error("Fetch error:", error); // log ra console
    Swal.fire("エラー", "サーバーに接続できませんでした。", "error");
  }
};

const handleSave = (api) => {
  Swal.fire({
    title: "変更を保存しますか？",
    showDenyButton: true,
    confirmButtonText: "保存",
    denyButtonText: `キャンセル`,
  }).then((result) => {
    if (result.isConfirmed) {
      handleEdit(api);
    } else if (result.isDenied) {
      Swal.fire("変更は保存されていません", "", "info");
    }
  });
};

formEdit.addEventListener("submit", (e) => {
  e.preventDefault();
  e.stopPropagation();
  const api = "../backend/edit_api.php";
  handleSave(api);
});
