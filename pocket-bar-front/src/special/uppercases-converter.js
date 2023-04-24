export function upperConverter(word) {
 word = word.toLowerCase();
 var array1 = word.split(' ');
 var newarray1 = [];

 for (let x = 0; x < array1.length; x++) {
  newarray1.push(array1[x].charAt(0).toUpperCase() + array1[x].slice(1));
 }
 return newarray1.join(' ');
}

// export default { upperConverter }
// Assume str = "the quick brown fox";
// The split() method is used to split a String object into an array of strings by separating the string into substrings.
//  console.log(str.split(' '));
// Output: ["the", "quick", "brown", "fox"]
// First substrings -> "the"
// Code to convert first character of the above sting to upper case -> array1[x].charAt(0).toUpperCase()
// console.log(array1[x].charAt(0).toUpperCase());[here x = 0]
// Output: "T"
// Rest part of the string "the" -> array1[x].slice(1)
// console.log(array1[0].slice(1));
// Output: "he"
// Final string:
// console.log(array1[0].charAt(0).toUpperCase() + array1[0].slice(1));
// Output: "The"
// Now insert the above string into another array:
// newarray1.push(array1[x].charAt(0).toUpperCase() + array1[x].slice(1));

// Used functions:
// The charAt() method is used to get the specified character from a string.
//  Syntax : str.charAt(index).Where index represents an integer between 0 and 1 - less - than the length of the string.
// The toUpperCase() method is used to convert the string value to uppercase.
// The slice() method returns a shallow copy of a portion of an array into a new array object.
// The push() method is used to add one or more elements to the end of an array and returns the new length of the array.

// After completing the for loop return the final string:
// return newarray1.join(' ');
// The join() method joins all elements of an array into a string.
