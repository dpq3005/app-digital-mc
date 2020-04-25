import {Endpoint, HttpService} from "../services/http/http.service";
import {concat, Observable, of, Subject} from "rxjs";
import {catchError, distinctUntilChanged, switchMap, tap} from "rxjs/operators";
import {Product} from "./product";
import {Merchant} from "./merchant";

export class DigitalMedicalChit {
  id: string;
  isDeleted: boolean = false;
  beneficiaryNric: string = null;
  beneficiaryName: string = null;

  isLoading = false;


  code: string = null;
  isExpired: boolean;
  isRedeemed: boolean;

  benefitProductId: string = null;
  productId = null;
  productName = null;
  merchantIds: string[] = null;

  product: Product = null;
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

  getProduct(): Product {
    if (this.product === null) {
      for (let i = 0; i < this.productOptions.length; i++) {
        if (this.productOptions[i].id == this.productId) {
          this.product = this.productOptions[i];
          return this.product;
        }
      }
    }
    return this.product;
  }

  redeem(merchantId, callback?) {
    if (merchantId !== null) {
      let url = `digital-medical-chits/${this.id}/redeem`;
      this.http.post(Endpoint.GLOBAL, ['digital-medical-chits', this.id, 'redeem'], {
        uuid: this.id,
        redeemedAtMerchantUuid: merchantId,
      }).subscribe(res => {
        console.log('redemption done', res);
        if (callback) {
          callback();
        }
      });
    }
  }

  save(callback?) {
    this.isLoading = true;
    if (this.id == null) {
      this.benefitProductId = this.getProduct().benefitProductId;

      if (this.merchants !== null && this.merchants.length > 0) {
        this.merchantIds = [];
        for (let i = 0; i < this.merchants.length; i++) {
          let merchant = this.merchants[i];
          this.merchantIds.push(merchant.id);
        }
      }

      this.http.post(Endpoint.GLOBAL, ['digital-medical-chits'], {
        beneficiaryNric: this.beneficiaryNric,
        beneficiaryName: this.beneficiaryName,
        product: this.productId,
        benefitProduct: this.benefitProductId,
        merchants: this.merchantIds
      }).subscribe(res => {
        console.log('save done', res);
        this.isLoading = false;
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

  populateFromApiRes(item) {
    this.id = item.uuid;
    this.beneficiaryName = item.beneficiaryName;
    this.id = item.uuid;
    this.beneficiaryNric = item.beneficiaryNric;
    this.productId = item.product;
    this.productName = item.productName;
    this.benefitProductId = item.benefitProduct;

    if (this.product === null) {
      this.product = new Product();
      this.product.initServices(this.http);
    }

    this.product.id = this.productId;
    this.product.benefitProductId = this.benefitProductId;
    this.product.name = this.productName;

    this.code = item.code;
    this.isExpired = item.expired;
    this.isRedeemed = item.redeemed;
  }

  load(callback?) {
    let url = "digital-medical-chits/" + this.id;
    this.isLoading = true;
    this.http.get(Endpoint.GLOBAL, [url]).pipe(catchError((err) => {
      this.isLoading = false;
      return (err);
    })).subscribe(res => {
      let item = res;
      this.populateFromApiRes(item);
      this.isLoading = false;
      if (callback) {
        callback();
      }
    })
    // }
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
  beneficiaryNameFilter: string = null;
  beneficiaryNricFilter: string = null;

  medicalChits: DigitalMedicalChit[];
  currentPage: number = 0;
  pageSize: number = 20;
  isLoading = false;
  isLastPage = false;

  http: HttpService = null;

  queryParams: string[] = null;

  initServices(http: HttpService) {
    this.http = http;
  }

  constructor() {
    this.medicalChits = [];
    this.queryParams = ['expired=0'];
  }

  appendItem(dmc: DigitalMedicalChit, index?) {
    // this.addItem(dmc, index, 'push');
    // this.medicalChits[_method](dmc);
    this.medicalChits.push(dmc);
  }

  clearItems() {
    this.medicalChits.length = 0;
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

  loadItemsFromFirstPage(callback?) {
    this.currentPage = 0;
    this.isLastPage = false;
    this.clearItems();
    this.loadItemsFromNextPage(callback);
  }

  loadItemsFromNextPage(callback?) {
    if (this.isLastPage) {
      return;
    }
    this.isLoading = true;
    this.currentPage++;
    let url = "digital-medical-chits?pageSize=" + this.pageSize + "&page=" + this.currentPage;
    if (this.beneficiaryNameFilter !== null) {
      url += '&beneficiaryName=' + this.beneficiaryNameFilter;
    }
    if (this.beneficiaryNricFilter !== null) {
      url += '&beneficiaryNric=' + this.beneficiaryNricFilter;
    }

    if (this.queryParams !== null) {
      url += '&' + this.queryParams.join('&');
    }

    // if (!this.isLoading) {
    this.http.get(Endpoint.GLOBAL, [url]).pipe(catchError((err) => {
      this.isLoading = false;
      return (err);
    })).subscribe(res => {
      if (res.length === 0 || res.length < this.pageSize) {
        this.isLastPage = true;
      }
      for (let i = 0; i < res.length; i++) {
        let item = res[i];
        let dmc = new DigitalMedicalChit();
        dmc.initServices(this.http);
        dmc.populateFromApiRes(item);
        this.appendItem(dmc);
      }
      this.isLoading = false;
      if (callback) {
        callback();
      }
    });
    // }
  }

}
