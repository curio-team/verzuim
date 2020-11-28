// src/controllers/modal-controller.js
import { Controller } from "stimulus";

export default class extends Controller {

    static targets = ["modal"];

    open(event) {
        event.preventDefault();
        this.modalTarget.classList.add("modal-open");
        this.modalTarget.setAttribute("style", "display: block;");
        this.modalTarget.classList.add("show");
        var backdrop = document.createElement('div');
        backdrop.classList.add('modal-backdrop', 'fade', 'show');
        document.body.appendChild(backdrop);
    }

    close() {
        this.modalTarget.classList.remove("modal-open");
        this.modalTarget.removeAttribute("style");
        this.modalTarget.classList.remove("show");
        document.getElementsByClassName("modal-backdrop")[0].remove();
    }
}
