import { Controller } from "stimulus"

export default class extends Controller {

	static targets = ["query", "list"]
  
	filter() {

		var query = this.queryTarget.value.toLowerCase();
		var display = this.data.get('display') ?? 'initial';
		this.listTargets.forEach((el, i) => {
			var key = el.innerHTML.toLowerCase();
			el.style.display = (key.includes(query)) ? display : "none";
		})

	}
}
