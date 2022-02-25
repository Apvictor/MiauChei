import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PetsSightedPageRoutingModule } from './pets-sighted-routing.module';

import { PetsSightedPage } from './pets-sighted.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PetsSightedPageRoutingModule
  ],
  declarations: [PetsSightedPage]
})
export class PetsSightedPageModule {}
