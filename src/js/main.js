import __initMenu from './_menu';
import __initPreload from "./_preload";
import __initUpload from './_upload';
import __initModal from "./_confirm-modal";

export default function init() {
  __initMenu();
  __initPreload();
  __initUpload();
  __initModal();
}
