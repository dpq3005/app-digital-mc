import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";

@Component({
  selector: 'app-logout',
  templateUrl: './logout.component.html',
  styleUrls: ['./logout.component.css']
})
export class LogoutComponent implements OnInit {

  constructor(private router: Router) {
  }

  ngOnInit(): void {
    let isSupervisor: boolean = false;
    if (localStorage.getItem('benefitProviderUuid') !== null) {
      isSupervisor = true;
    }

    localStorage.clear();

    if (isSupervisor) {
      this.router.navigate(['supervisor', 'login']);
    } else {
      this.router.navigate(['merchant', 'login']);
    }
  }

}
