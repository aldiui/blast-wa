import React from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import {
    Button,
    Card,
    CardBody,
    CardFooter,
    CardHeader,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Heading,
    Icon,
    Input,
    SimpleGrid,
    Image,
    Text,
    Textarea,
} from "@chakra-ui/react";
import { ArrowLeftIcon, BookmarkIcon } from "@heroicons/react/16/solid";
import AdminLayout from "../../../Layouts/AdminLayout";

const Pengaturan = ({ auth, sessions, pengaturan }) => {
    const { data, setData, put, processing, errors } = useForm({
        nama: pengaturan.nama,
        email: pengaturan.email,
        alamat: pengaturan.alamat,
        no_telepon: pengaturan.no_telepon,
        syahriyah: pengaturan.syahriyah,
        uang_makan: pengaturan.uang_makan,
        field_trip: pengaturan.field_trip,
        daftar_ulang: pengaturan.daftar_ulang,
    });

    const submit = (e) => {
        e.preventDefault();
        put(`/pengaturan`);
    };

    return (
        <AdminLayout auth={auth} sessions={sessions}>
            <Head title="Edit Pengaturan" />
            <Card w="full" p={2} h={"auto"}>
                <CardHeader pb={0}>
                    <Heading size="md" fontWeight="bold">
                        Edit Pengaturan
                    </Heading>
                </CardHeader>
                <form onSubmit={submit}>
                    <CardBody pb={0}>
                        <SimpleGrid columns={{ base: 1, xl: 2 }} spacing={6}>
                            <FormControl isInvalid={errors.nama}>
                                <FormLabel htmlFor="nama" fontSize={"sm"}>
                                    Nama
                                    <Text display={"inline"} color="red">
                                        *
                                    </Text>
                                </FormLabel>
                                <Input
                                    type="text"
                                    id="nama"
                                    value={data.nama}
                                    onChange={(e) =>
                                        setData("nama", e.target.value)
                                    }
                                />
                                {errors.nama && (
                                    <FormErrorMessage fontSize={"xs"}>
                                        {errors.nama}
                                    </FormErrorMessage>
                                )}
                            </FormControl>
                            <FormControl isInvalid={errors.alamat}>
                                <FormLabel htmlFor="alamat" fontSize={"sm"}>
                                    Alamat
                                    <Text display={"inline"} color="red">
                                        *
                                    </Text>
                                </FormLabel>
                                <Textarea
                                    id="alamat"
                                    value={data.alamat}
                                    onChange={(e) =>
                                        setData("alamat", e.target.value)
                                    }
                                ></Textarea>
                                {errors.alamat && (
                                    <FormErrorMessage fontSize={"xs"}>
                                        {errors.alamat}
                                    </FormErrorMessage>
                                )}
                            </FormControl>
                            <FormControl isInvalid={errors.no_telepon}>
                                <FormLabel htmlFor="no_telepon" fontSize={"sm"}>
                                    No. Telepon
                                    <Text display={"inline"} color="red">
                                        *
                                    </Text>
                                </FormLabel>
                                <Input
                                    type="number"
                                    id="no_telepon"
                                    value={data.no_telepon}
                                    onChange={(e) =>
                                        setData("no_telepon", e.target.value)
                                    }
                                />
                                {errors.no_telepon && (
                                    <FormErrorMessage fontSize={"xs"}>
                                        {errors.no_telepon}
                                    </FormErrorMessage>
                                )}
                            </FormControl>
                            <FormControl isInvalid={errors.email}>
                                <FormLabel htmlFor="email" fontSize={"sm"}>
                                    Email
                                    <Text display={"inline"} color="red">
                                        *
                                    </Text>
                                </FormLabel>
                                <Input
                                    type="email"
                                    id="email"
                                    value={data.email}
                                    onChange={(e) =>
                                        setData("email", e.target.value)
                                    }
                                />
                                {errors.email && (
                                    <FormErrorMessage fontSize={"xs"}>
                                        {errors.email}
                                    </FormErrorMessage>
                                )}
                            </FormControl>
                            <FormControl isInvalid={errors.syahriyah}>
                                <FormLabel htmlFor="syahriyah" fontSize={"sm"}>
                                    Syahriyah
                                    <Text display={"inline"} color="red">
                                        *
                                    </Text>
                                </FormLabel>
                                <Input
                                    type="number"
                                    id="syahriyah"
                                    value={data.syahriyah}
                                    onChange={(e) =>
                                        setData("syahriyah", e.target.value)
                                    }
                                />
                                {errors.syahriyah && (
                                    <FormErrorMessage fontSize={"xs"}>
                                        {errors.syahriyah}
                                    </FormErrorMessage>
                                )}
                            </FormControl>
                            <FormControl isInvalid={errors.uang_makan}>
                                <FormLabel htmlFor="uang_makan" fontSize={"sm"}>
                                    Uang Makan
                                    <Text display={"inline"} color="red">
                                        *
                                    </Text>
                                </FormLabel>
                                <Input
                                    type="number"
                                    id="uang_makan"
                                    value={data.uang_makan}
                                    onChange={(e) =>
                                        setData("uang_makan", e.target.value)
                                    }
                                />
                                {errors.uang_makan && (
                                    <FormErrorMessage fontSize={"xs"}>
                                        {errors.uang_makan}
                                    </FormErrorMessage>
                                )}
                            </FormControl>
                            <FormControl isInvalid={errors.field_trip}>
                                <FormLabel htmlFor="field_trip" fontSize={"sm"}>
                                    Field Trip
                                    <Text display={"inline"} color="red">
                                        *
                                    </Text>
                                </FormLabel>
                                <Input
                                    type="number"
                                    id="field_trip"
                                    value={data.field_trip}
                                    onChange={(e) =>
                                        setData("field_trip", e.target.value)
                                    }
                                />
                                {errors.field_trip && (
                                    <FormErrorMessage fontSize={"xs"}>
                                        {errors.field_trip}
                                    </FormErrorMessage>
                                )}
                            </FormControl>
                            <FormControl isInvalid={errors.daftar_ulang}>
                                <FormLabel
                                    htmlFor="daftar_ulang"
                                    fontSize={"sm"}
                                >
                                    Daftar Ulang
                                    <Text display={"inline"} color="red">
                                        *
                                    </Text>
                                </FormLabel>
                                <Input
                                    type="number"
                                    id="daftar_ulang"
                                    value={data.daftar_ulang}
                                    onChange={(e) =>
                                        setData("daftar_ulang", e.target.value)
                                    }
                                />
                                {errors.daftar_ulang && (
                                    <FormErrorMessage fontSize={"xs"}>
                                        {errors.daftar_ulang}
                                    </FormErrorMessage>
                                )}
                            </FormControl>
                        </SimpleGrid>
                    </CardBody>
                    <CardFooter>
                        <Button
                            type="submit"
                            colorScheme="green"
                            isLoading={processing}
                            loadingText="Simpan"
                        >
                            <Icon as={BookmarkIcon} mr={2} />
                            Simpan
                        </Button>
                        <Button
                            as={Link}
                            href={"/pengaturan"}
                            colorScheme="gray"
                            ml={3}
                        >
                            <Icon as={ArrowLeftIcon} mr={2} />
                            Kembali
                        </Button>
                    </CardFooter>
                </form>
            </Card>
        </AdminLayout>
    );
};

export default Pengaturan;
