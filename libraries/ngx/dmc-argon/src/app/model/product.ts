import {Endpoint, HttpService} from "../services/http/http.service";
import {catchError} from "rxjs/operators";
import {DmcItem} from "./dmc-item";

export class Product extends DmcItem {
  benefitProductId: string;
  description: string;
  shortDescription: string;
  telemedEnabled: boolean;
  telemedPrice: number;

  isLoading = false;
  http: HttpService = null;

  initServices(http: HttpService) {
    this.http = http;
  }

  loadByBenefitProductId(callback?) {
    let url = "benefit-products/" + this.benefitProductId;
    this.isLoading = true;
    this.http.get(Endpoint.GLOBAL, [url]).pipe(catchError((err) => {
      this.isLoading = false;
      return (err);
    })).subscribe(res => {
      this.shortDescription = res.shortDescription;
      this.description = res.description;
      this.telemedEnabled = res.telemedEnabled;
      this.telemedPrice = res.telemedPrice;
      this.isLoading = false;
      if (callback) {
        callback();
      }
    })
  };
}
