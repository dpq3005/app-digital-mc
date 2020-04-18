import {Endpoint, HttpService} from "../services/http/http.service";
import {Observable} from "rxjs";

export class DigitalMedicalChit {
  id: string;
  memberNric: string = null;
  memberName: string = null;
  productId = null;

  productOptions: Product[] = [];

  http: HttpService = null;

  initServices(http: HttpService) {
    this.http = http;
  }

  populateFromNric() {
    // /benefit-providers/15e6f99ba1de1c/find-one-beneficiary-by-nric/13/lalana(NAING)042215
    let uuid = localStorage.getItem('benefitProviderUuid');
    let nric = this.memberNric;
    try {
      this.http.get(Endpoint.ENTITY, "benefit-providers/" + uuid + "/find-one-beneficiary-by-nric/" + nric + "?page=1").subscribe((res: any) => {
        console.log(res);
        this.memberName = res.name;
      });
    } catch (err) {

    }
  }

  populateProductOptions() {
    let uuid = localStorage.getItem('benefitProviderUuid');
    try {
      this.http.get(Endpoint.PRODUCT, "benefit-providers/" + uuid + "/find-benefit-products?page=1").subscribe((res: any) => {
        this.productOptions = [];
        let p: Product;
        for (let i = 0; i < res.length; i++) {
          p = new Product();
          p.id = res[i].uuid;
          p.name = res[i].name;
          this.productOptions.push(p);
        }

        if (res.length === 1) {
          this.productId = res[0].uuid;
        }

      });
    } catch (error) {

    }
  }
}

export class Product {
  id: string;
  name: string;
}
