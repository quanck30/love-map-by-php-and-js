/**
 * 2025/10/20
 * DINH BINH QUAN
 */

/**
 * 汎用のHTML要素作成関数
 * @param {String} tag HTMLタグ
 * @param {Object} param1 オプションオブジェクト
 * @param {string} [param1.className] CSSクラス名
 * @param {string} [param1.text] 要素内のテキスト
 * @param {Object} [param1.attrs] 設定する属性
 * @param {Object} [param1.events] 設定するイベントリスナー )
 * @returns {HTMLElement} 作成したHTML要素
 */
export const createElementHelper = (tag, { className = "", text = "", attrs = {}, events = {} } = {}) => {
  const element = document.createElement(tag);
  if (className) element.className = className;
  if (text) element.textContent = text;

  Object.entries(attrs).forEach(([key, value]) => element.setAttribute(key, value));
  Object.entries(events).forEach(([event, handler]) => element.addEventListener(event, handler));
  return element;
};
