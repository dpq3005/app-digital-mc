import {Component, OnInit} from '@angular/core';
import {Credentials, SupervisorCredentials} from "../../../security/credentials";
import {AuthService} from "../../../security/auth.service";
import {catchError} from "rxjs/operators";
import {Observable, ObservableInput} from "rxjs";
import {Router} from "@angular/router";

@Component({
  selector: 'app-supervisor-login',
  templateUrl: './supervisor-login.component.html',
  styleUrls: ['./supervisor-login.component.css']
})
export class SupervisorLoginComponent implements OnInit {

  loading = false;
  errorMessage = '';
  credentials: SupervisorCredentials;

  constructor(public authService: AuthService, private router: Router) {
    this.credentials = new SupervisorCredentials();
  }

  ngOnInit(): void {
    var body = document.getElementsByTagName("body")[0];
    body.classList.remove("bg-default");
    body.classList.add('bg-wellness');

    if (this.authService.isAuthenticated()) {
      this.router.navigate(['supervisor', 'dmc', 'list']);
    }
  }

  login() {
    this.loading = true;
    this.errorMessage = '';
    this.authService.authenticate(this.credentials).pipe(catchError((err, caught): ObservableInput<any> => {
      this.loading = false;
      this.errorMessage = err.message;
      return new Observable();
    })).subscribe(jwt => {
      this.loading = false;
      localStorage.setItem('token', jwt.token);
      localStorage.setItem('benefitProviderUuid', jwt.benefitProviderUuid);
      localStorage.setItem('credentials', JSON.stringify(this.credentials));

      var body = document.getElementsByTagName("body")[0];
      body.classList.add("bg-default");
      body.classList.remove('bg-wellness');
      this.router.navigate(['supervisor', 'dmc', 'list']);
    });
  }
}
