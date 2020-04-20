import {Endpoint, HttpService} from "../services/http/http.service";
import {concat, Observable, of, Subject} from "rxjs";
import {catchError, distinctUntilChanged, switchMap, tap} from "rxjs/operators";

export class DigitalMedicalChit {
  id: string;
  memberNric: string = null;
  memberName: string = null;
  productId = null;
  merchants: Merchant[] = null;

  merchantLoading = false;

  productOptions: Product[] = [];
  merchantOptions: Merchant[];
  merchantOptions$: Observable<Merchant[]>;
  merchantOptionInput$ = new Subject<string>();

  http: HttpService = null;

  initServices(http: HttpService) {
    this.http = http;
  }

  save() {
    if (this.id == null) {
      this.http.post(Endpoint.GLOBAL, ['digital-medical-chits'],{
        nric: this.memberNric,
        name: this.memberName,
        product: this.productId,
        merchants: this.merchants
      }).subscribe(res => {
        console.log('save done', res);
      });
    }
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

  populateMerchantOptions() {
    // scheduled([o1, o2, o3], scheduler).pipe(concatAll()
    this.merchantOptions$ = <Observable<Merchant[]>>concat(
      of([]), // default items
      this.merchantOptionInput$.pipe(
        distinctUntilChanged(),
        tap(() => this.merchantLoading = true),
        switchMap(term => this.http.get(Endpoint.PRODUCT, "products/" + this.productId + "/find-merchants-by-product-uuid?organisationName=" + term + "&pageSize=10").pipe(
          catchError(() => of([])), // empty list on error
          tap(() => this.merchantLoading = false)
        ))
      )
    );

    try {
      this.http.get(Endpoint.PRODUCT, "products/" + this.productId + "/find-merchants-by-product-uuid?pageSize=100").subscribe((res: any) => {
        this.merchantOptions = [];
        let p: Product;
        for (let i = 0; i < res.length; i++) {
          p = new Merchant();
          p.id = res[i].uuid;
          p.name = res[i].name;
          this.merchantOptions.push(p);
        }

      });
    } catch (error) {
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
          this.populateMerchantOptions();
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

export class Merchant {
  id: string;
  name: string;
}
