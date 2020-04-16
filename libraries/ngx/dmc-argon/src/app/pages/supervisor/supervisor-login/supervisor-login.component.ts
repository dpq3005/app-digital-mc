import {Component, OnInit} from '@angular/core';
import {Credentials, SupervisorCredentials} from "../../../security/credentials";
import {AuthService} from "../../../security/auth.service";

@Component({
  selector: 'app-supervisor-login',
  templateUrl: './supervisor-login.component.html',
  styleUrls: ['./supervisor-login.component.css']
})
export class SupervisorLoginComponent implements OnInit {

  loading = false;

  credentials: SupervisorCredentials;

  constructor(public authService: AuthService) {
    this.credentials = new SupervisorCredentials();
  }

  ngOnInit(): void {
  }

  login() {
    this.loading = true;
    this.authService.authenticate(this.credentials).subscribe(jwt => {
      this.loading = false;
      localStorage.setItem('token', jwt.token);
      localStorage.setItem('credentials', JSON.stringify(this.credentials));
    });
  }
}
