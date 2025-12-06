# Smart‑Edu Data Model & Exam/Classroom Flow — Project Documentation  
*(based on our design discussion; updated Dec 6, 2025)*

---

## 1) Purpose of This Document
This document captures the **final, clarified data‑model intent** for Smart‑Edu after the database redesign.  
It is meant to be saved in `docs/` and used as the single source of truth for:

- How educational structures (section → grade → branch → field → subfield → subjects → topics) are represented.
- How **classrooms** are created and what tables they depend on.
- How **exam creation** works across the three exam types.
- Who is responsible for maintaining the educational taxonomy (Super Admin vs Teacher).

---

## 2) Educational Taxonomy (Hierarchical Structure)

Smart‑Edu is built around the idea that **choosing a class context automatically defines the accessible subjects**, because all curriculum data is pre‑seeded into the system.

### 2.1 Hierarchy
The hierarchy is:

1. **Section (مقطع تحصیلی)**  
   Example: *Secondary / متوسطه دوم*

2. **Grade (پایه)**  
   Example: *11th grade / یازدهم*

3. **Branch (شاخه کلی آموزشی)**  
   Examples:
   - *Theoretical / نظری*
   - *Technical‑Vocational / فنی‌وحرفه‌ای*
   - *(Kardanesh / کاردانش — not active yet, added later)*

4. **Field (رشته)**  
   Depends on Branch. Examples:
   - **Theoretical** → *Experimental (تجربی), Math‑Physics (ریاضی‑فیزیک), Humanities (انسانی), Islamic Studies (معارف)*
   - **Technical‑Vocational** → *Accounting (حسابداری), Computer (کامپیوتر)*

5. **Subfield (زیررشته / گرایش)**  
   Optional, depends on Field.  
   Example: future expansion when a field splits into multiple paths.

6. **SubjectType (نوع درس / تک‌درس یا جامع)**  
   - *Single subject class (تک درس)*  
   - *Comprehensive class (جامع)* — includes **all subjects of the grade+field**, and teacher acts as academic counselor.

7. **Subjects (دروس)**  
   Predefined for each Grade+Branch+Field (+Subfield):  
   Example subject: *“New Technology Applications / کاربرد فناوری‌های نوین”*

8. **Topics (پودمان / فصل / سرفصل)**  
   Each subject contains multiple topics.  
   Example: *5 modules / ۵ پودمان*

### 2.2 Key Design Rule
> **Selecting Grade + Branch + Field (+Subfield)** gives access to all related subjects automatically.  
Teachers **do not define subjects**, they only pick from seeded curriculum.

---

## 3) Classroom Creation — What Data It Touches

### 3.1 Goal
A classroom represents **a real educational class context**:

- Its academic level & curriculum scope are fixed at creation time.
- Students join via join_code.
- Exams inside the class inherit its subject scope.

### 3.2 Tables Involved
To create a classroom, the system reads/writes:

**Core classroom table**
- `classrooms`
  - `teacher_id`
  - `section_id`
  - `grade_id`
  - `branch_id`
  - `field_id`
  - `subfield_id` (optional)
  - `subject_type_id`
  - `classroom_type` (single vs comprehensive)
  - `join_code`
  - `is_active`
  - `metadata` (JSON)

**Curriculum reference tables (read‑only for teacher)**
- `sections`
- `grades`
- `branches`
- `fields`
- `subfields`
- `subject_types`
- `subjects`
- `topics`

**Membership**
- `classroom_user` (pivot)
  - `classroom_id`
  - `user_id`

**Subjects attached to classroom**
- `classroom_subject` (pivot)
  - `classroom_id`
  - `subject_id`

### 3.3 Behavior
- **Single‑subject class:** teacher selects **one subject**, and record is added to `classroom_subject`.  
- **Comprehensive class:** teacher selects **grade+field**, then system attaches **all subjects of that scope** to `classroom_subject`.

---

## 4) Example Walkthrough (Section → Topic)

A concrete example we agreed on:

**Secondary (متوسطه دوم)**  
→ **11th grade (یازدهم)**  
→ **Technical‑Vocational (فنی‌وحرفه‌ای)**  
→ **Computer (کامپیوتر)**  
→ **Subject: “New Technology Applications” (کاربرد فناوری‌های نوین)**  
→ **5 modules = 5 topics (۵ پودمان = ۵ topic)**

This is the exact mental model the UI should match.

---

## 5) Exam Creation Flow (3 Exam Types)

### 5.1 Exam Types
Teachers can create exams in three modes:

1. **Public Exam (آزمون عمومی)**
   - Not tied to a classroom.
   - Must follow the multi‑step wizard **sequentially**.
   - User completes each step from 1 → 8 in order.

2. **Classroom Exam (آزمون کلاسی)**
   - Tied to a specific classroom.
   - Wizard can be prefilled up to step 8 automatically from classroom context.
   - After step 8, teacher jumps to:
     - question details
     - final review
     - save/publish

3. **Comprehensive Counseling Exam (آزمون جامع مشاوره‌ای)**
   - Also linked to a classroom, but classroom is “comprehensive”.
   - Scope = **all subjects of that grade+field**.
   - Teacher acts as a counselor overseeing overall progress.

### 5.2 Practical Wizard Rule
> **If Public is selected:** system enforces step‑by‑step progression.  
> **If Classroom or Comprehensive is selected:** wizard auto‑fills academic scope and jumps after step 8 to exam details & saving.

---

## 6) Roles & Responsibility: Super Admin vs Teacher

### 6.1 Permanent Super Admin
We have a **fixed Super Admin** user with full privileges.

### 6.2 Super Admin Responsibilities
Only Super Admin manages the curriculum taxonomy:

- Creating/editing:
  - sections, grades
  - branches, fields, subfields
  - subject types
  - subjects
  - topics
- Seeding/importing official curriculum data.
- Maintaining correctness of curriculum relationships.

### 6.3 Teacher Responsibilities
Teachers **do not build curriculum**. They:

- Create classrooms by selecting from existing taxonomy.
- Choose subject(s) only by ticking from the ready list.
- Create exams based on classroom scope.
- Monitor student performance.

### 6.4 Why This Matters
This separation:

- Prevents curriculum corruption.
- Makes teacher UX faster and cleaner.
- Keeps the system scalable for future additions (new fields, grades, topics).

---

## 7) Implications for UI/UX

- Classroom creation UI must:
  - feel like selecting a path in a tree
  - show filtered choices based on previous selection
  - end with subjects list (single vs comprehensive behavior)

- Exam creation UI must:
  - show 3 entry types clearly
  - enforce or skip steps based on type
  - always derive scope from seeded curriculum

---

## 8) Future Expansion Notes

- **Kardanesh Branch** is out of scope now, but taxonomy is ready to include it later.
- Multi‑teacher classrooms can be enabled later by using `classroom_user` + role filters.
- Subfields can be activated once fields require specializations.

---

## 9) Summary (One‑Line)
Smart‑Edu’s redesigned database ensures that **teachers only choose academic context, not define it**, and the system derives subjects/topics automatically from seeded curriculum, enabling reliable classroom and multi‑type exam flows.

---
