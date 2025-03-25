import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { StudentListComponent } from './student-list/student-list.component';
import { AddStudentComponent } from './add-student/add-student.component';
import { EditStudentComponent } from './edit-student/edit-student.component';
import { TestListComponent } from './test-list/test-list.component';
import { AddTestComponent } from './add-test/add-test.component';
import { EditTestComponent } from './edit-test/edit-test.component';
import { AutoEcoleListComponent } from './auto-ecole-list/auto-ecole-list.component';
import { AddAutoEcoleComponent } from './add-auto-ecole/add-auto-ecole.component';
import { EditAutoEcoleComponent } from './edit-auto-ecole/edit-auto-ecole.component';
import { AuthGuard } from './auth.guard';

const routes: Routes = [
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  { path: 'login', component: LoginComponent },

  // Routes pour la gestion des étudiants
  { path: 'students', component: StudentListComponent, canActivate: [AuthGuard] },
  { path: 'add-student', component: AddStudentComponent, canActivate: [AuthGuard] },
  { path: 'edit-student/:id', component: EditStudentComponent, canActivate: [AuthGuard] },

  // Routes pour la gestion des tests
  { path: 'tests', component: TestListComponent, canActivate: [AuthGuard] },
  { path: 'add-test', component: AddTestComponent, canActivate: [AuthGuard] },
  { path: 'edit-test/:id', component: EditTestComponent, canActivate: [AuthGuard] },

  // Routes pour la gestion des auto-écoles
  { path: 'auto-ecoles', component: AutoEcoleListComponent, canActivate: [AuthGuard] },
  { path: 'add-auto-ecole', component: AddAutoEcoleComponent, canActivate: [AuthGuard] },
  { path: 'edit-auto-ecole/:id', component: EditAutoEcoleComponent, canActivate: [AuthGuard] },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }