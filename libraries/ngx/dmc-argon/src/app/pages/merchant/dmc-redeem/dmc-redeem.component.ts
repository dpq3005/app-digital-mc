import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {DigitalMedicalChit} from "../../../model/digital-medical-chit";
import {HttpService} from "../../../services/http/http.service";
import {Product} from "../../../model/product";
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {Merchant} from "../../../model/merchant";

@Component({
  selector: 'app-dmc-redeem',
  templateUrl: './dmc-redeem.component.html',
  styleUrls: ['./dmc-redeem.component.css']
})
export class DmcRedeemComponent implements OnInit {

  dmc: DigitalMedicalChit;
  isLoading = false;
  loggedInMerchant: Merchant;

  constructor(private modalService: NgbModal, private route: ActivatedRoute, http: HttpService, private router: Router) {
    this.loggedInMerchant = new Merchant();
    this.loggedInMerchant.initServices(http);

    this.dmc = new DigitalMedicalChit();
    this.dmc.initServices(http)
  }

  ngOnInit(): void {
    let id = this.route.snapshot.paramMap.get('id');
    this.loggedInMerchant.id = localStorage.getItem('merchantUuid');
    this.loggedInMerchant.load();

    this.dmc.id = id;
    this.dmc.load(() => {
      this.dmc.product.loadByBenefitProductId();
    });
  }

  redeem() {
    this.router.navigate(['merchant', 'dmc', this.route.snapshot.paramMap.get('id'), 'redeem', 'validate-pin']);
  }
}
