import {DmcItem} from "./dmc-item";
import {Endpoint, HttpService} from "../services/http/http.service";
import {catchError} from "rxjs/operators";

export class Merchant extends DmcItem {
  private http: HttpService;
  isLoading = false;

  constructor() {
    super();
  }

  initServices(http: HttpService) {
    this.http = http;
  }

  load(callback?) {
    this.http.get(Endpoint.GLOBAL, ['merchants', this.id]).pipe(catchError((err) => {
      this.isLoading = false;
      return (err);
    })).subscribe(res => {
      this.name = res.name;
      // this.merchants.push(merchant);
      this.isLoading = false;
      if (callback) {
        callback();
      }
    });
  }
}
