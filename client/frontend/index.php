<!-- 2025/10/10 DINH BINH QUAN -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>ラブマップ</title>
</head>

<body class="overflow-hidden">
  <main id="main" class="m-full h-screen">
    <div class="relative bg-cover bg-center w-full h-full" style="background-image: url('./assets/image/background_image.jpg')">
      <!-- Overlay (optional cho tối background) -->
      <div class="absolute inset-0 bg-black/20"></div>

      <div class="relative z-10 flex items-center justify-center h-full px-4">
        <!-- FORM -->
        <form 
          id="search_form" 
          action="./search_page.php" 
          method="POST"
          class="
            flex h-12 rounded-lg bg-white border border-gray-200 shadow-md
            w-full max-w-2xl 
            sm:h-14 sm:max-w-xl
            md:max-w-2xl
          "
        >
          <!-- KEYWORD -->
          <input 
            name="keyword" 
            id="keyword_input" 
            type="text" 
            placeholder="検索するキーワード..."
            class="p-3 flex-1 focus:outline-none text-sm sm:text-base"
          />

          <div class="w-px h-[70%] my-auto bg-gray-300"></div>

          <!-- AREA -->
          <div id="area_wrapper" class="flex-1 relative">
            <input 
              name="area" 
              id="area_input" 
              type="text" 
              placeholder="場所..."
              class="p-3 w-full focus:outline-none text-sm sm:text-base"
            />
          </div>

          <div class="w-px h-[70%] my-auto bg-gray-300"></div>

          <!-- BUTTON -->
          <button 
            type="submit"
            class="
              w-12 h-full flex items-center justify-center
              hover:bg-pink-200 active:bg-pink-300
              transition-all duration-200 ease-in-out
              rounded-r-lg
            "
          >
            <i class="ri-search-line text-xl"></i>
          </button>
        </form>
      </div>
    </div>
  </main>

  <script type="module" src="./js/areaHandle.js"></script>
</body>

</html>
