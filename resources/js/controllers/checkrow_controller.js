import { Controller } from "stimulus"

export default class extends Controller {

	static targets = ["box"]
  
	toggle(event) {
		if(event.target != this.boxTarget)
		{
			this.boxTarget.checked = this.boxTarget.checked ? false : true;
		}
		
	}
}
