import {Component, OnInit} from '@angular/core';
import {DigitalMedicalChit} from "../../../model/digital-medical-chit";
import {HttpService} from "../../../services/http/http.service";
import {Router} from "@angular/router";
import {Merchant} from "../../../model/merchant";

@Component({
  selector: 'app-telemed-single',
  templateUrl: './telemed-single.component.html',
  styleUrls: ['./telemed-single.component.css']
})
export class TelemedSingleComponent implements OnInit {

  isNricReady: boolean = false;
  loading: boolean = false;

  products: any[];
  merchants: any[];

  telemed: DigitalMedicalChit;

  showMerchantSelect = false;

  constructor(private http: HttpService, private router: Router) {
  }

  trackByFn(item: Merchant) {
    return item.id;
  }

  ngOnInit() {
    this.telemed = new DigitalMedicalChit();
    this.telemed.telemedEnabled = true;
    this.telemed.initServices(this.http);
    // this.telemed.productId = '123';
    this.telemed.populateProductOptions();
  }

  onProductChange($event) {
    if (this.telemed.productId !== null) {
      this.telemed.populateMerchantOptions();
    }
    if (!this.telemed.getProduct().telemedEnabled) {
      this.telemed.telemedEnabled = false;
    }
  }

  createTelemed() {
    console.log('create telemed', this.telemed);
    this.telemed.save(() => {
      localStorage.setItem('beneficiaryName', this.telemed.beneficiaryName);
      this.router.navigate(['supervisor', 'telemed', 'create', 'successful']);
    });
  }

  handleNric() {
    this.loading = true;
    this.telemed.populateFromNric();
    this.loading = false;
    this.isNricReady = true;
  }

}
