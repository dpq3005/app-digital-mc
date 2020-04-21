import {Endpoint, HttpService} from "../services/http/http.service";
import {concat, Observable, of, Subject} from "rxjs";
import {catchError, distinctUntilChanged, switchMap, tap} from "rxjs/operators";

export class DigitalMedicalChit {
  id: string;
  isDeleted: boolean = false;
  beneficiaryNric: string = null;
  beneficiaryName: string = null;


  code: string = null;
  isExpired: boolean;
  isRedeemed: boolean;

  benefitProductId: string = null;
  productId = null;
  productName = null;
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

  save(callback?) {
    if (this.id == null) {
      for (let i = 0; i < this.productOptions.length; i++) {
        if (this.productOptions[i].id == this.productId) {
          this.benefitProductId = this.productOptions[i].benefitProductId;
          break;
        }
      }

      this.http.post(Endpoint.GLOBAL, ['digital-medical-chits'], {
        beneficiaryNric: this.beneficiaryNric,
        beneficiaryName: this.beneficiaryName,
        product: this.productId,
        benefitProduct: this.benefitProductId,
        merchants: this.merchants
      }).subscribe(res => {
        console.log('save done', res);
        if (callback) {
          callback();
        }
      });
    }
  }

  delete(callback?) {
    try {
      return this.http.delete(Endpoint.GLOBAL, ["digital-medical-chits/" + this.id]).subscribe((res: any) => {
        this.isDeleted = true;
        if (callback) {
          callback();
        }
      });
    } catch (error) {
      console.log(error);
    }
  }

  populateFromNric() {
    // /benefit-providers/15e6f99ba1de1c/find-one-beneficiary-by-nric/13/lalana(NAING)042215
    let uuid = localStorage.getItem('benefitProviderUuid');
    let nric = this.beneficiaryNric;
    try {
      this.http.get(Endpoint.ENTITY, ["benefit-providers/" + uuid + "/find-one-beneficiary-by-nric/" + nric + "?page=1"]).subscribe((res: any) => {
        console.log(res);
        this.beneficiaryName = res.name;
      });
    } catch (err) {

    }
  }

  populateMerchantOptions() {
    // TODO scheduled([o1, o2, o3], scheduler).pipe(concatAll()
    this.merchantOptions$ = <Observable<Merchant[]>>concat(
      of([]), // default items
      this.merchantOptionInput$.pipe(
        distinctUntilChanged(),
        tap(() => this.merchantLoading = true),
        switchMap(term => this.http.get(Endpoint.PRODUCT, ["products/" + this.productId + "/find-merchants-by-product-uuid?organisationName=" + term + "&pageSize=10"]).pipe(
          catchError(() => of([])), // empty list on error
          tap(() => this.merchantLoading = false)
        ))
      )
    );

    try {
      this.http.get(Endpoint.PRODUCT, ["products/" + this.productId + "/find-merchants-by-product-uuid?pageSize=100"]).subscribe((res: any) => {
        this.merchantOptions = [];
        let p: Merchant;
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
      this.http.get(Endpoint.PRODUCT, ["benefit-providers/" + uuid + "/find-benefit-products?page=1"]).subscribe((res: any) => {
        this.productOptions = [];
        let p: Product;
        for (let i = 0; i < res.length; i++) {
          p = new Product();
          p.id = res[i].productUuid;
          p.name = res[i].name;
          p.benefitProductId = res[i].uuid;
          this.productOptions.push(p);
        }

        if (res.length === 1) {
          this.productId = res[0].productUuid;
          this.populateMerchantOptions();
        }

      });
    } catch (error) {

    }
  }

}

export class DigitalMedicalChitCollection {
  medicalChits: DigitalMedicalChit[];
  currentPage: number = 0;
  isLoading = false;

  http: HttpService = null;

  initServices(http: HttpService) {
    this.http = http;
  }

  constructor() {
    this.medicalChits = [];
  }

  appendItem(dmc: DigitalMedicalChit, index?) {
    // this.addItem(dmc, index, 'push');
    // this.medicalChits[_method](dmc);
    this.medicalChits.push(dmc);
  }

  deleteItem(dmc: DigitalMedicalChit) {
    console.log('deleteItem')
    dmc.delete(() => {
      let i = this.medicalChits.indexOf(dmc);
      console.log('i is ' + i);
      if (i >= 0) {
        this.medicalChits.splice(i, 1);
      }
    });
  }

  loadItemsFromNextPage() {
    this.isLoading = true;

    // if (!this.isLoading) {
    this.currentPage++;
    this.http.get(Endpoint.GLOBAL, ["digital-medical-chits?pageSize=20&page=" + this.currentPage]).pipe(catchError((err) => {
      this.isLoading = false;
      return (err);
    })).subscribe(res => {

      for (let i = 0; i < res.length; i++) {
        let item = res[i];
        let dmc = new DigitalMedicalChit();
        dmc.initServices(this.http);

        dmc.beneficiaryName = item.beneficiaryName;
        dmc.id = item.uuid;
        dmc.beneficiaryNric = item.beneficiaryNric;
        dmc.productId = item.product;
        dmc.productName = item.productName;

        dmc.code = item.code;
        dmc.isExpired = item.expired;
        dmc.isRedeemed = item.redeemed;
        this.appendItem(dmc);
      }
      this.isLoading = false;

    })
    // }
  }

}

export class DmcItem {
  id: string;
  name: string;
}

export class Product extends DmcItem {
  benefitProductId: string;
}

export class Merchant extends DmcItem {

}
