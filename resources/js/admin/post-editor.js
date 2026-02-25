// TipTap Editor Logic
import { Editor } from "@tiptap/core";
import CodeBlockLowlight from "@tiptap/extension-code-block-lowlight";
import Image from "@tiptap/extension-image";
import Link from "@tiptap/extension-link";
import Placeholder from "@tiptap/extension-placeholder";
import StarterKit from "@tiptap/starter-kit";
import "highlight.js/styles/github-dark.css";
import { common, createLowlight } from "lowlight";

// Setup Lowlight
const lowlight = createLowlight(common);

// Initialize Editor
document.addEventListener("DOMContentLoaded", () => {
    const editorElement = document.getElementById("tiptap-editor");
    if (!editorElement) return;

    const contentInput = document.getElementById("content");
    const initialContent = contentInput ? contentInput.value : "";

    const editor = new Editor({
        element: editorElement,
        extensions: [
            StarterKit.configure({
                codeBlock: false, // disable default code block
            }),
            Placeholder.configure({
                placeholder: "Write your technical article here...",
            }),
            Link.configure({
                openOnClick: false,
                HTMLAttributes: {
                    class: "text-primary-400 underline decoration-primary-500/30 hover:decoration-primary-500",
                },
            }),
            Image.configure({
                inline: true,
                allowBase64: true,
                HTMLAttributes: {
                    class: "rounded-lg border border-gray-700 my-4 max-w-full",
                },
            }),
            CodeBlockLowlight.configure({
                lowlight,
                HTMLAttributes: {
                    class: "bg-[#111] rounded border border-gray-700 p-4 font-mono text-sm leading-relaxed overflow-x-auto",
                },
            }),
        ],
        content: initialContent,
        editorProps: {
            attributes: {
                class: "prose prose-invert prose-sm max-w-none focus:outline-none min-h-[500px] text-gray-300 font-mono p-4",
            },
        },
        onUpdate: ({ editor }) => {
            if (contentInput) {
                contentInput.value = editor.getHTML();
            }
        },
    });

    // Toolbar Logic
    const toolbar = document.getElementById("tiptap-toolbar");

    function createBtn(label, action, isActiveCheck) {
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className =
            "px-2 py-1 text-xs font-mono text-gray-400 hover:text-white hover:bg-gray-800 rounded transition-colors border border-transparent";
        btn.innerHTML = label;
        btn.onclick = (e) => {
            e.preventDefault();
            action();
            editor.view.focus();
        };

        editor.on("transaction", () => {
            if (isActiveCheck && isActiveCheck()) {
                btn.classList.add(
                    "bg-primary-900/20",
                    "text-primary-400",
                    "border-primary-500/30",
                );
            } else {
                btn.classList.remove(
                    "bg-primary-900/20",
                    "text-primary-400",
                    "border-primary-500/30",
                );
            }
        });

        if (toolbar) toolbar.appendChild(btn);
    }

    // Formatting Buttons
    createBtn(
        "<b>B</b>",
        () => editor.chain().focus().toggleBold().run(),
        () => editor.isActive("bold"),
    );
    createBtn(
        "<i>I</i>",
        () => editor.chain().focus().toggleItalic().run(),
        () => editor.isActive("italic"),
    );
    createBtn(
        "<strike>S</strike>",
        () => editor.chain().focus().toggleStrike().run(),
        () => editor.isActive("strike"),
    );
    createBtn(
        "H1",
        () => editor.chain().focus().toggleHeading({ level: 1 }).run(),
        () => editor.isActive("heading", { level: 1 }),
    );
    createBtn(
        "H2",
        () => editor.chain().focus().toggleHeading({ level: 2 }).run(),
        () => editor.isActive("heading", { level: 2 }),
    );
    createBtn(
        "H3",
        () => editor.chain().focus().toggleHeading({ level: 3 }).run(),
        () => editor.isActive("heading", { level: 3 }),
    );

    createBtn(
        "• List",
        () => editor.chain().focus().toggleBulletList().run(),
        () => editor.isActive("bulletList"),
    );
    createBtn(
        "1. List",
        () => editor.chain().focus().toggleOrderedList().run(),
        () => editor.isActive("orderedList"),
    );

    createBtn(
        "{Code}",
        () => editor.chain().focus().toggleCode().run(),
        () => editor.isActive("code"),
    );
    createBtn(
        "&lt;Pre&gt;",
        () => editor.chain().focus().toggleCodeBlock().run(),
        () => editor.isActive("codeBlock"),
    );
    createBtn(
        "Quote",
        () => editor.chain().focus().toggleBlockquote().run(),
        () => editor.isActive("blockquote"),
    );

    // Horizontal Rule (Separator)
    createBtn(
        "---",
        () => editor.chain().focus().setHorizontalRule().run(),
        () => false, // No active state for HR
    );
});
