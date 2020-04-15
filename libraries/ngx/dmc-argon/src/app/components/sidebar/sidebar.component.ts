import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';

declare interface RouteInfo {
  path: string;
  title: string;
  icon: string;
  class: string;
  children: RouteInfo[];
}

export const ROUTES: RouteInfo[] = [
  // {path: '/dashboard', title: 'Dashboard', icon: 'ni-tv-2 text-primary', class: '', children: []},
  // {path: '/icons', title: 'Icons', icon: 'ni-planet text-blue', class: '', children: []},
  {
    path: '#', title: 'Supervisor', icon: 'ni-planet text-blue', class: '', children: [
      {path: '/supervisor/login', title: 'Login', icon: 'ni-single-02 text-yellow', class: '', children: []},
      {path: '/supervisor/dmc/create', title: 'Create Digital Medical Chit', icon: 'ni-fat-add text-yellow', class: '', children: []},
      {path: '/supervisor/dmc/list', title: 'Manage Digital Medical Chit', icon: 'ni-bullet-list-67 text-red', class: '', children: []},

    ]
  },
  {
    path: '#', title: 'Centre', icon: 'ni-planet text-blue', class: '', children: [
      {path: '/merchant/login', title: 'Login', icon: 'ni-single-02 text-yellow', class: '', children: []},
      {path: '/supervisor/dmc/list', title: 'Redeem Digital Medical Chit', icon: 'ni-check-bold text-red', class: '', children: []},
      // {path: '/supervisor/dmc/redeem', title: 'Create Digital Medical Chit', icon: 'ni-fat-add text-yellow', class: '', children: []},
    ]
  },
  // {path: '/maps', title: 'Maps', icon: 'ni-pin-3 text-orange', class: '', children: []},
  // {path: '/user-profile', title: 'User profile', icon: 'ni-single-02 text-yellow', class: '', children: []},
  // {path: '/tables', title: 'Tables', icon: 'ni-bullet-list-67 text-red', class: '', children: []},
  {path: '/logout', title: 'Logout', icon: 'ni-button-power text-info', class: '', children: []},
  // {path: '/register', title: 'Register', icon: 'ni-circle-08 text-pink', class: '', children: []}
];

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {

  public menuItems: any[];
  public isCollapsed = true;

  constructor(private router: Router) {
  }

  ngOnInit() {
    this.menuItems = ROUTES.filter(menuItem => menuItem);
    this.router.events.subscribe((event) => {
      this.isCollapsed = true;
    });
  }
}
