import {Component, OnInit} from '@angular/core';
import {ModalDismissReasons, NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {catchError} from "rxjs/operators";
import {Observable, ObservableInput} from "rxjs";
import {AuthService} from "../../../../security/auth.service";
import {ActivatedRoute, Router} from "@angular/router";
import {MerchantCredentials} from "../../../../security/credentials";
import {DigitalMedicalChit} from "../../../../model/digital-medical-chit";
import {HttpService} from "../../../../services/http/http.service";

@Component({
  selector: 'app-confirm-dmc-redemption',
  templateUrl: './confirm-dmc-redemption.component.html',
  styleUrls: ['./confirm-dmc-redemption.component.css']
})
export class ConfirmDmcRedemptionComponent implements OnInit {
  digit1: number;
  digit2: number;
  digit3: number;
  digit4: number;
  digit5: number;
  digit6: number;

  closeResult = '';

  loginStatus: string;

  isLoading: boolean = false;
  errorMessage = '';

  dmc: DigitalMedicalChit;

  isRedeemed = false;

  constructor(private route: ActivatedRoute, private modalService: NgbModal, private authService: AuthService, private  router: Router, private http: HttpService) {
    this.dmc = new DigitalMedicalChit();
    this.dmc.initServices(http);
  }

  ngOnInit(): void {
    this.dmc.id = this.route.snapshot.paramMap.get('id');
    this.dmc.load(() => {
      this.dmc.product.loadByBenefitProductId();
      this.isRedeemed = this.dmc.isRedeemed;
    });
  }

  verify() {
    let credentials = new MerchantCredentials()

    this.loginStatus = null;
    let token = localStorage.getItem('token');
    let user = this.authService.jwtHelper.decodeToken(token);
    let merchantUuid = user.username;
    credentials.uuid = merchantUuid;
    credentials.pin = this.digit1 + '' + this.digit2 + '' + this.digit3 + '' + this.digit4 + '' + this.digit5 + '' + this.digit6;

    this.isLoading = true;
    this.errorMessage = '';
    this.authService.authenticate(credentials).pipe(catchError((err, caught): ObservableInput<any> => {
      this.isLoading = false;
      this.loginStatus = this.errorMessage = 'Invalid PIN enterred';
      console.log('errrrrrrrrrrrrr ', err);
      return new Observable();
    })).subscribe(jwt => {
      this.isLoading = false;
      this.modalService.dismissAll();
      // this.router.navigate(['merchant', 'dmc', 'list']);
      this.dmc.redeem(merchantUuid, credentials.pin, () => {
        // this.router.navigate(['merchant', 'dmc', 'list']);
        this.isRedeemed = true;
        this.isLoading = false;
        console.log('done');
      });
    });
  }

  openModal(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title'}).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }

  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return `with: ${reason}`;
    }
  }
}
