import {Injectable} from '@angular/core';
import {CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router} from '@angular/router';
import {Observable, ObservableInput} from 'rxjs';
import {AuthService} from "../auth.service";
import {catchError} from "rxjs/operators";

@Injectable({
  providedIn: 'root'
})
export class MerchantAuthGuard implements CanActivate {

  constructor(private authService: AuthService, private router: Router) {
  }

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    if (!this.authService.isAuthenticated()) {
      try {
        this.authService.reAuthenticate().pipe(catchError((err, caught): ObservableInput<any> => {
          this.router.navigate(['merchant', 'login?uuid=' + localStorage.getItem('merchantUuid')]);
          return new Observable();
        })).subscribe(jwt => {
          localStorage.setItem('token', jwt.token);
          let user = this.authService.getUser();
          localStorage.setItem('merchantUuid', user.username);
          return true;
        });
      } catch (exception) {
        console.log('Re-Auth error', exception);
        this.router.navigate(['supervisor', 'login']);
      }
    }
    return true;
  }

}
