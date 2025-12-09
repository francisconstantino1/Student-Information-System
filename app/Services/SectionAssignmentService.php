<?php

namespace App\Services;

use App\Models\Section;
use App\Models\User;

class SectionAssignmentService
{
    /**
     * Get or create a section and assign student to it
     * Automatically creates new sections when existing ones are full
     */
    public function assignSection(User $student, string $course, string $yearLevel, ?string $semester = null, ?string $academicYear = null): ?Section
    {
        // Get course abbreviation (e.g., "Information Technology" -> "IT")
        $courseAbbrev = $this->getCourseAbbreviation($course);

        // Extract year number from year level (e.g., "1st Year" -> "1")
        $yearNumber = $this->extractYearNumber($yearLevel);

        // Find existing sections for this course and year level
        $existingSections = Section::where('course', $course)
            ->where('year_level', $yearLevel)
            ->orderBy('name')
            ->get();

        // Check each section to find one with available space
        foreach ($existingSections as $section) {
            $currentCount = $section->students()->count();

            if ($currentCount < $section->max_students) {
                // This section has space, assign student to it
                $student->update(['section_id' => $section->id]);

                return $section;
            }
        }

        // All existing sections are full, create a new one
        $nextSectionLetter = $this->getNextSectionLetter($existingSections, $courseAbbrev, $yearNumber);
        $sectionName = "{$courseAbbrev}-{$yearNumber}{$nextSectionLetter}";

        // Create new section
        $newSection = Section::create([
            'name' => $sectionName,
            'course' => $course,
            'year_level' => $yearLevel,
            'semester' => $semester,
            'academic_year' => $academicYear,
            'max_students' => 10, // Default capacity
        ]);

        // Assign student to the new section
        $student->update(['section_id' => $newSection->id]);

        return $newSection;
    }

    /**
     * Get course abbreviation from full course name
     */
    private function getCourseAbbreviation(string $course): string
    {
        // Common course abbreviations mapping
        $abbreviations = [
            'Information Technology' => 'IT',
            'Computer Science' => 'CS',
            'Computer Engineering' => 'CE',
            'Information Systems' => 'IS',
            'Software Engineering' => 'SE',
            'Data Science' => 'DS',
            'Cybersecurity' => 'CY',
            'Business Administration' => 'BA',
            'Accountancy' => 'ACC',
            'Marketing' => 'MKT',
            'Finance' => 'FIN',
            'Economics' => 'ECO',
            'Psychology' => 'PSY',
            'Education' => 'EDU',
            'Nursing' => 'NUR',
            'Engineering' => 'ENG',
            'Architecture' => 'ARCH',
            'Medicine' => 'MED',
        ];

        // Check if we have a mapping
        if (isset($abbreviations[$course])) {
            return $abbreviations[$course];
        }

        // If no mapping, generate abbreviation from first letters of words
        $words = explode(' ', $course);
        $abbrev = '';
        foreach ($words as $word) {
            if (! empty($word)) {
                $abbrev .= strtoupper(substr($word, 0, 1));
            }
        }

        // Limit to 4 characters max
        return substr($abbrev, 0, 4);
    }

    /**
     * Extract year number from year level string
     */
    private function extractYearNumber(string $yearLevel): string
    {
        // Extract number from strings like "1st Year", "2nd Year", etc.
        if (preg_match('/(\d+)/', $yearLevel, $matches)) {
            return $matches[1];
        }

        return '1'; // Default to 1 if no number found
    }

    /**
     * Get the next section letter (A, B, C, etc.)
     */
    private function getNextSectionLetter($existingSections, string $courseAbbrev, string $yearNumber): string
    {
        if ($existingSections->isEmpty()) {
            return 'A'; // First section
        }

        // Extract letters from existing section names
        $usedLetters = [];
        // Escape special regex characters in course abbrev and year number
        $escapedAbbrev = preg_quote($courseAbbrev, '/');
        $escapedYear = preg_quote($yearNumber, '/');
        $pattern = "/^{$escapedAbbrev}-{$escapedYear}([A-Z])$/";

        foreach ($existingSections as $section) {
            if (preg_match($pattern, $section->name, $matches)) {
                $usedLetters[] = $matches[1];
            }
        }

        // Find the next available letter
        $alphabet = range('A', 'Z');

        foreach ($alphabet as $letter) {
            if (! in_array($letter, $usedLetters)) {
                return $letter;
            }
        }

        // If all letters A-Z are used, start with AA, AB, etc.
        // This handles cases beyond Z (though unlikely with 10 students per section)
        $count = count($usedLetters);
        if ($count >= 26) {
            $firstLetterIndex = intval(($count - 26) / 26);
            $secondLetterIndex = ($count - 26) % 26;

            return $alphabet[$firstLetterIndex].$alphabet[$secondLetterIndex];
        }

        // Should not reach here, but return Z as fallback
        return 'Z';
    }

    /**
     * Get the default max students for a section
     */
    public function getDefaultMaxStudents(): int
    {
        return 10; // Default capacity per section
    }
}
