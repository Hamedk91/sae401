import { Component, OnInit } from '@angular/core';
import { StudentService } from '../student.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-student-list',
  templateUrl: './student-list.component.html',
  styleUrls: ['./student-list.component.css'],
  standalone: false
})
export class StudentListComponent implements OnInit {
  students: any[] = [];

  constructor(private studentService: StudentService, private router: Router) {}

  ngOnInit(): void {
    this.loadStudents();
  }

  loadStudents() {
    this.studentService.getStudents().subscribe(
      (data: any) => {
        this.students = data.eleves; 
      },
      (error) => {
        console.error('Erreur lors du chargement des élèves', error);
      }
    );
  }

  deleteStudent(id: number) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet élève ?')) {
      this.studentService.deleteStudent(id).subscribe(
        () => {
          this.loadStudents(); // Recharger la liste après suppression
        },
        (error) => {
          console.error('Erreur lors de la suppression', error);
        }
      );
    }
  }
  

  editStudent(id: number) {
    this.router.navigate(['/edit-student', id]);
  }

  addStudent() {
    this.router.navigate(['/add-student']);
  }
}