import {Component, OnInit} from '@angular/core';
import {SupervisorCredentials} from "../../../security/credentials";
import {AuthService} from "../../../security/auth.service";

@Component({
  selector: 'app-supervisor-login',
  templateUrl: './supervisor-login.component.html',
  styleUrls: ['./supervisor-login.component.css']
})
export class SupervisorLoginComponent implements OnInit {

  credentials: SupervisorCredentials;

  constructor(public authService: AuthService) {
    this.credentials = new SupervisorCredentials();
  }

  ngOnInit(): void {
  }

  login() {
    this.authService.authenticate(this.credentials).subscribe(jwt => {
      console.log(jwt);
    });
  }

}
