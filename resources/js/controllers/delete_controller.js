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

    continue() {
        var id = this.data.get("id");
        var resource = this.data.get("resource");
        console.log(id);
        axios.delete('/' + resource + '/' + id)
        .then(function (response) {
            if(response.data == "ok")
            {
                window.location.href = '/' + resource;
            }
        })
        .catch(function (error) {
            console.log(error);
        });
    }
}