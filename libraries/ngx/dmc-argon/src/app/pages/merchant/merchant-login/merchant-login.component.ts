import {Component, OnInit} from '@angular/core';
import {ModalDismissReasons, NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {Location} from "@angular/common";
import {ActivatedRoute, Router} from "@angular/router";
import {catchError} from "rxjs/operators";
import {Observable, ObservableInput} from "rxjs";
import {AuthService} from "../../../security/auth.service";
import {MerchantCredentials, SupervisorCredentials} from "../../../security/credentials";

@Component({
  selector: 'app-merchant-login',
  templateUrl: './merchant-login.component.html',
  styleUrls: ['./merchant-login.component.css']
})
export class MerchantLoginComponent implements OnInit {

  digit1: number;
  digit2: number;
  digit3: number;
  digit4: number;
  digit5: number;
  digit6: number;

  closeResult = '';

  loginStatus: string;

  credentials: MerchantCredentials;

  ngOnInit(): void {
  }

  constructor(private modalService: NgbModal, private route: ActivatedRoute, private authService: AuthService, private  router: Router) {
    this.credentials = new MerchantCredentials();
  }

  isLoading: boolean = false;
  errorMessage = '';

  verify() {
    let merchantUuid = this.route.snapshot.queryParamMap.get('uuid');
    this.credentials.uuid = merchantUuid;
    this.credentials.pin = this.digit1 + '' + this.digit2 + '' + this.digit3 + '' + this.digit4 + '' + this.digit5 + '' + this.digit6;

    this.isLoading = true;
    this.errorMessage = '';
    this.authService.authenticate(this.credentials).pipe(catchError((err, caught): ObservableInput<any> => {
      this.isLoading = false;
      this.errorMessage = err.message;
      return new Observable();
    })).subscribe(jwt => {
      this.isLoading = false;
      localStorage.setItem('token', jwt.token);
      localStorage.setItem('merchantUuid', this.credentials.uuid);
      localStorage.setItem('credentials', JSON.stringify(this.credentials));
      this.modalService.dismissAll();
      this.router.navigate(['merchant', 'dmc', 'list']);
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
