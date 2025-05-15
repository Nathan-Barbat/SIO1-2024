<?php
// utils/Validator.php
// Classe utilitaire pour la validation des données

class Validator {
    private $errors = [];
    private $data = [];
    
    // Constructeur
    public function __construct($data) {
        $this->data = $data;
    }
    
    // Méthode pour vérifier si un champ est requis
    public function required($field, $fieldName = null) {
        $name = $fieldName ?: $field;
        
        if (!isset($this->data[$field]) || empty(trim($this->data[$field]))) {
            $this->errors[$field] = "Le champ {$name} est obligatoire.";
        }
        
        return $this;
    }
    
    // Méthode pour vérifier si un champ est un email valide
    public function email($field, $fieldName = null) {
        $name = $fieldName ?: $field;
        
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = "Le champ {$name} doit être une adresse email valide.";
            }
        }
        
        return $this;
    }
    
    // Méthode pour vérifier si un champ a une longueur minimale
    public function minLength($field, $length, $fieldName = null) {
        $name = $fieldName ?: $field;
        
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (strlen($this->data[$field]) < $length) {
                $this->errors[$field] = "Le champ {$name} doit contenir au moins {$length} caractères.";
            }
        }
        
        return $this;
    }
    
    // Méthode pour vérifier si un champ a une longueur maximale
    public function maxLength($field, $length, $fieldName = null) {
        $name = $fieldName ?: $field;
        
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (strlen($this->data[$field]) > $length) {
                $this->errors[$field] = "Le champ {$name} ne doit pas dépasser {$length} caractères.";
            }
        }
        
        return $this;
    }
    
    // Méthode pour vérifier si un champ est un nombre entier
    public function integer($field, $fieldName = null) {
        $name = $fieldName ?: $field;
        
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
                $this->errors[$field] = "Le champ {$name} doit être un nombre entier.";
            }
        }
        
        return $this;
    }
    
    // Méthode pour vérifier si un champ est compris entre deux valeurs
    public function between($field, $min, $max, $fieldName = null) {
        $name = $fieldName ?: $field;
        
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            $value = $this->data[$field];
            if (is_numeric($value)) {
                if ($value < $min || $value > $max) {
                    $this->errors[$field] = "Le champ {$name} doit être compris entre {$min} et {$max}.";
                }
            }
        }
        
        return $this;
    }
    
    // Méthode pour vérifier si deux champs sont identiques
    public function matches($field, $matchField, $fieldName = null, $matchFieldName = null) {
        $name = $fieldName ?: $field;
        $matchName = $matchFieldName ?: $matchField;
        
        if (isset($this->data[$field]) && isset($this->data[$matchField])) {
            if ($this->data[$field] !== $this->data[$matchField]) {
                $this->errors[$field] = "Les champs {$name} et {$matchName} doivent être identiques.";
            }
        }
        
        return $this;
    }
    
    // Méthode pour vérifier si un champ est dans une liste de valeurs
    public function inList($field, $list, $fieldName = null) {
        $name = $fieldName ?: $field;
        
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!in_array($this->data[$field], $list)) {
                $this->errors[$field] = "La valeur du champ {$name} n'est pas valide.";
            }
        }
        
        return $this;
    }
    
    // Méthode pour ajouter une erreur personnalisée
    public function addError($field, $message) {
        $this->errors[$field] = $message;
        
        return $this;
    }
    
    // Méthode pour vérifier si la validation est passée
    public function passes() {
        return empty($this->errors);
    }
    
    // Méthode pour vérifier si la validation a échoué
    public function fails() {
        return !$this->passes();
    }
    
    // Méthode pour obtenir les erreurs
    public function getErrors() {
        return $this->errors;
    }
    
    // Méthode pour obtenir une erreur spécifique
    public function getError($field) {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }
    
    // Méthode pour obtenir le premier message d'erreur
    public function getFirstError() {
        if (!empty($this->errors)) {
            return reset($this->errors);
        }
        return null;
    }
}