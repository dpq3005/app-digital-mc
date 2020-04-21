import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";
import {AuthService, Role} from "../../security/auth.service";

@Component({
  selector: 'app-logout',
  templateUrl: './logout.component.html',
  styleUrls: ['./logout.component.css']
})
export class LogoutComponent implements OnInit {

  constructor(private router: Router, private authService: AuthService) {
  }

  ngOnInit(): void {
    let isSupervisor: boolean = false;
    if (this.authService.isGranted(Role.SUPERVISOR)) {
      isSupervisor = true;
    }

    if (isSupervisor) {
      this.authService.logout();
      this.router.navigate(['supervisor', 'login']);
    } else {
      let afterLogoutUrl = '/merchant/login?uuid=' + localStorage.getItem('merchantUuid');
      this.authService.logout();
      this.router.navigateByUrl(afterLogoutUrl);
    }
  }

}
