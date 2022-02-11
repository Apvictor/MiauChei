import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { RouteReuseStrategy } from '@angular/router';

import { IonicModule, IonicRouteStrategy } from '@ionic/angular';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

//http imports
import { HttpClientModule } from '@angular/common/http'

//Mascaras
import { NgxMaskModule } from 'ngx-mask';

// Plugin para camera funcionar na Web
import { defineCustomElements } from '@ionic/pwa-elements/loader';

defineCustomElements(window);

@NgModule({
  declarations: [AppComponent],
  entryComponents: [],
  imports: [BrowserModule, IonicModule.forRoot(), AppRoutingModule, HttpClientModule, NgxMaskModule.forRoot({ dropSpecialCharacters: false })],
  providers: [{ provide: RouteReuseStrategy, useClass: IonicRouteStrategy },],
  bootstrap: [AppComponent],
})
export class AppModule { }
