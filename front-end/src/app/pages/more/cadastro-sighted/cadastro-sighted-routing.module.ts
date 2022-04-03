import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CadastroSightedPage } from './cadastro-sighted.page';

const routes: Routes = [
  {
    path: '',
    component: CadastroSightedPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CadastroSightedPageRoutingModule {}
