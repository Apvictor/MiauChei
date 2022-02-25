import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PetsSightedPage } from './pets-sighted.page';

const routes: Routes = [
  {
    path: '',
    component: PetsSightedPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PetsSightedPageRoutingModule {}
