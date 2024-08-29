import React from "react";
import {
    Button,
    Modal,
    ModalOverlay,
    ModalContent,
    ModalHeader,
    ModalFooter,
    ModalBody,
    ModalCloseButton,
    useDisclosure,
    Icon,
} from "@chakra-ui/react";
import { useState } from "react";
import { router } from "@inertiajs/react";
import { TrashIcon, XMarkIcon } from "@heroicons/react/16/solid";

const DeleteButton = ({ uri }) => {
    const { isOpen, onOpen, onClose } = useDisclosure();
    const [isDeleting, setIsDeleting] = useState(false);

    const onDeleteItem = () => {
        setIsDeleting(true);
        router.delete(uri, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                onClose();
                setIsDeleting(false);
            },
        });
    };

    return (
        <>
            <Button
                onClick={onOpen}
                colorScheme="red"
                variant="solid"
                isLoading={isDeleting}
                loadingText="Hapus"
                size="sm"
            >
                <Icon as={TrashIcon} mr={2} />
                Hapus
            </Button>

            <Modal isOpen={isOpen} onClose={onClose} size="md">
                <ModalOverlay />
                <ModalContent m={6}>
                    <ModalHeader>Konfirmasi Penghapusan</ModalHeader>
                    <ModalCloseButton />
                    <ModalBody>
                        Apakah Anda yakin ingin menghapus data ini?
                    </ModalBody>

                    <ModalFooter textAlign={"center"}>
                        <Button
                            size={"sm"}
                            colorScheme="gray"
                            onClick={onClose}
                        >
                            <Icon as={XMarkIcon} mr={2} />
                            Batal
                        </Button>
                        <Button
                            size={"sm"}
                            colorScheme="red"
                            onClick={onDeleteItem}
                            ml={3}
                            isLoading={isDeleting}
                        >
                            <Icon as={TrashIcon} mr={2} />
                            Hapus
                        </Button>
                    </ModalFooter>
                </ModalContent>
            </Modal>
        </>
    );
};

export default DeleteButton;
