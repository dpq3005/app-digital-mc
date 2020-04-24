import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {DigitalMedicalChit} from "../../../model/digital-medical-chit";
import {HttpService} from "../../../services/http/http.service";
import {Product} from "../../../model/product";

@Component({
  selector: 'app-dmc-redeem',
  templateUrl: './dmc-redeem.component.html',
  styleUrls: ['./dmc-redeem.component.css']
})
export class DmcRedeemComponent implements OnInit {

  dmc: DigitalMedicalChit;
  isLoading = false;

  constructor(private route: ActivatedRoute, http: HttpService) {
    this.dmc = new DigitalMedicalChit();
    this.dmc.initServices(http)
  }

  ngOnInit(): void {
    let id = this.route.snapshot.paramMap.get('id');
    this.dmc.id = id;
    this.dmc.load(() => {
      this.dmc.product.loadByBenefitProductId();
    });
  }

  redeem() {
    let merchantUuid = localStorage.getItem('merchantUuid');
    this.isLoading = true;
    this.dmc.redeem(merchantUuid, () => {
      this.isLoading = false;
      console.log('done');
    });
  }

}
