import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['bigImage']
    
    resize(event) {
        this.bigImageTarget.src = event.target.src;
        this.bigImageTarget.alt = event.target.alt;
    }
}
